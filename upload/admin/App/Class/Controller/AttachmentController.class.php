<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   多媒体管理控制器($)*/

!defined('DYHB_PATH') && exit;

require_once(Core_Extend::includeFile('function/Attachment_Extend'));

/** 导入Home模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/home/App/Class/Model');

/** 定义Home的语言包 */
define('__APP_ADMIN_LANG__',WINDSFORCE_PATH.'/app/home/App/Lang/Admin');

class AttachmentController extends InitController{

	protected $_arrAttachmentcategory=array();

	public function filter_(&$arrMap){
		$arrMap['attachment_name']=array('like',"%".G::getGpc('attachment_name')."%");

		// 用户
		$nUid=intval(G::getGpc('uid','G'));
		if($nUid){
			$oUser=UserModel::F('user_id=?',$nUid)->getOne();

			if(!empty($oUser['user_id'])){
				$arrMap['user_id']=$nUid;
				$this->assign('oUser',$oUser);
			}
		}

		// 类型
		$sType=trim(G::getGpc('type','G'));
		if($sType){
			$arrMap['attachment_extension']=$sType;
			$this->assign('sType',$sType);
		}

		// 专辑
		$nCid=G::getGpc('cid','G');
		if($nCid!==null){
			$arrAttachmentcategory=array(Dyhb::L('默认专辑','Controller/Attachment'),0);
			
			if($nCid>0){
				$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nCid)->getOne();
				if(!empty($oAttachmentcategory['attachmentcategory_id'])){
					$arrAttachmentcategory=array($oAttachmentcategory['attachmentcategory_name'],$nCid);
				}
			}
				
			$arrMap['attachmentcategory_id']=$nCid;
			$this->assign('arrAttachmentcategory',$arrAttachmentcategory);
		}
	}

	public function add(){
		$this->E(Dyhb::L('后台无法上传附件','Controller/Attachment').'<br/><a href="'.__ROOT__.'/index.php?app=home&c=attachment&a=add" target="_blank">'.Dyhb::L('前往上传','Controller/Attachment').'</a>');
	}

	public function cover(){
		$nId=intval(G::getGpc('id','G'));
	
		if(empty($nId)){
			$this->E(Dyhb::L('没有待设置的照片ID','Controller/Attachment'));
		}

		$oAttachment=AttachmentModel::F('attachment_id=?',$nId)->getOne();
		if(!empty($oAttachment['attachment_id'])){
			if($oAttachment['attachmentcategory_id']>0){
				$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$oAttachment['attachmentcategory_id'])->getOne();

				if(empty($oAttachmentcategory['attachmentcategory_id'])){
					$this->E(Dyhb::L('照片的专辑不存在','Controller/Attachment'));
				}

				$oAttachmentcategory->attachmentcategory_cover=$nId;
				$oAttachmentcategory->save(0,'update');

				if($oAttachmentcategory->isError()){
					$this->E($oAttachmentcategory->getErrorMessage());
				}

				$this->S(Dyhb::L('专辑封面设置成功','Controller/Attachment'));
			}else{
				$this->E(Dyhb::L('默认专辑不需要设置封面','Controller/Attachment'));
			}
		}else{
			$this->E(Dyhb::L('待设置的照片不存在','Controller/Attachment'));
		}
	}

	public function forbid($sModel=null,$sId=null,$bApp=false){
		$this->change_status_('recommend',0);
	}

	public function resume($sModel=null,$sId=null,$bApp=false){
		$this->change_status_('recommend',1);
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				$this->delete_attachment_($nId);
			}
		}
	}

	public function aForeverdelete($sId){
		$arrAttachmentcategory=$this->_arrAttachmentcategory;

		if(is_array($arrAttachmentcategory)){
			foreach($arrAttachmentcategory as $nAttachmentcategory){
				$this->update_attachmentnum_($nAttachmentcategory);
			}
		}
	}

	protected function delete_attachment_($nId){
		if(empty($nId)){
			$this->E(Dyhb::L('你没有选择你要删除的附件','Controller/Attachment'));
		}

		$oAttachment=AttachmentModel::F('attachment_id=?',$nId)->getOne();
		if(empty($oAttachment['attachment_id'])){
			$this->E(Dyhb::L('你要删除的附件不存在','Controller/Attachment'));
		}

		// 删除附件图片
		$sFilepath=WINDSFORCE_PATH.'/data/upload/attachment/'.$oAttachment['attachment_savepath'].'/'.$oAttachment['attachment_savename'];
		$sThumbfilepath=WINDSFORCE_PATH.'/data/upload/attachment/'.$oAttachment['attachment_thumbpath'].'/'.$oAttachment['attachment_savename'];

		if(is_file($sFilepath)){
			@unlink($sFilepath);
		}

		if(is_file($sThumbfilepath)){
			@unlink($sThumbfilepath);
		}

		// 记录附件专辑ID
		if($oAttachment['attachmentcategory_id']>0 && !in_array($oAttachment['attachmentcategory_id'],$this->_arrAttachmentcategory)){
			$this->_arrAttachmentcategory[]=$oAttachment['attachmentcategory_id'];
		}
	}

	protected function update_attachmentnum_($nAttachmentcategoryid){
		// 更新附件专辑附件数量统计
		$nAttachmentcategoryid=intval($nAttachmentcategoryid);

		if($nAttachmentcategoryid>0){
			$oAttachmentcategory=Dyhb::instance('AttachmentcategoryModel');
			$oAttachmentcategory->updateAttachmentnum($nAttachmentcategoryid);

			if($oAttachmentcategory->isError()){
				$this->E($oAttachmentcategory->getErrorMessage());
			}
		}
	}

	protected function cache_site_(){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('site');
	}

}
