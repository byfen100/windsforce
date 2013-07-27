<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   多媒体专辑管理控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入附件函数 */
require_once(Core_Extend::includeFile('function/Attachment_Extend'));

class AttachmentcategoryController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['attachmentcategory_name']=array('like',"%".G::getGpc('attachmentcategory_name')."%");

		// 用户
		$nUid=intval(G::getGpc('uid','G'));
		if($nUid){
			$oUser=UserModel::F('user_id=?',$nUid)->getOne();

			if(!empty($oUser['user_id'])){
				$arrMap['user_id']=$nUid;
				$this->assign('oUser',$oUser);
			}
		}
	}

	public function uncover(){
		$nId=intval(G::getGpc('id','G'));

		if(empty($nId)){
			$this->E(Dyhb::L('没有待取消封面的专辑ID','Controller'));
		}

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nId)->getOne();
		if(empty($oAttachmentcategory['attachmentcategory_id'])){
			$this->E(Dyhb::L('待取消封面的专辑不存在','Controller'));
		}

		$oAttachmentcategory->attachmentcategory_cover='0';
		$oAttachmentcategory->save(0,'update');

		if($oAttachmentcategory->isError()){
			$this->E($oAttachmentcategory->getErrorMessage());
		}

		$this->S(Dyhb::L('专辑封面删除成功','Controller'));
	}

	public function forbid($sModel=null,$sId=null,$bApp=false){
		$this->change_status_('recommend',0);
	}

	public function resume($sModel=null,$sId=null,$bApp=false){
		$this->change_status_('recommend',1);
	}

	public function add(){
		$this->E(Dyhb::L('后台无法创建专辑','Controller').'<br/><a href="'.Core_Extend::windsforceOuter('app=home&c=attachment&a=my_attachmentcategory').'" target="_blank">'.Dyhb::L('前往创建','Controller').'</a>');
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				$this->delete_attachmentcategory_($nId);
			}
		}
	}

	protected function delete_attachmentcategory_($nAttachmentcategoryid){
		if(empty($nAttachmentcategoryid)){
			$this->E(Dyhb::L('你没有选择你要删除的专辑','Controller'));
		}

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nAttachmentcategoryid)->getOne();
		if(empty($oAttachmentcategory['attachmentcategory_id'])){
			$this->E(Dyhb::L('你要删除的专辑不存在','Controller'));
		}

		$nTotalRecord=AttachmentModel::F('attachmentcategory_id=?',$oAttachmentcategory['attachmentcategory_id'])->all()->getCounts();
		if($nTotalRecord>0){
			$this->E(Dyhb::L('专辑含有照片，请先删除照片后再删除专辑','Controller'));
		}
	}

}
