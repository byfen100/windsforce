<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   删除附件操作($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DeleteattachmentController extends Controller{

	protected $_nAttachmentcategoryid=0;
	
	public function index($nId=''){
		if(empty($nId)){
			$nAttachmentid=intval(G::getGpc('id','G'));
		}else{
			$nAttachmentid=$nId;
		}

		if(empty($nAttachmentid)){
			$this->E(Dyhb::L('你没有选择你要删除的附件','Controller/Attachment'));
		}

		$oAttachment=AttachmentModel::F('attachment_id=?',$nAttachmentid)->getOne();
		if(empty($oAttachment['attachment_id'])){
			$this->E(Dyhb::L('你要删除的附件不存在','Controller/Attachment'));
		}

		if($oAttachment['user_id']!=$GLOBALS['___login___']['user_id']){
			$this->E(Dyhb::L('你不能删除别人的附件','Controller/Attachment'));
		}

		$this->_nAttachmentcategoryid=$oAttachment['attachmentcategory_id'];

		// 删除附件图片
		$sFilepath=WINDSFORCE_PATH.'/data/upload/attachment/'.$oAttachment['attachment_savepath'].'/'.$oAttachment['attachment_savename'];
		$sThumbfilepath=WINDSFORCE_PATH.'/data/upload/attachment/'.$oAttachment['attachment_thumbpath'].'/'.$oAttachment['attachment_savename'];

		if(is_file($sFilepath)){
			@unlink($sFilepath);
		}

		if(is_file($sThumbfilepath)){
			@unlink($sThumbfilepath);
		}

		$oAttachment->destroy();

		$this->cache_site_();

		if(!$nId){
			$this->S(Dyhb::L('附件删除成功','Controller/Attachment'));
		}
	}

	public function all(){
		$arrAttachmentid=G::getGpc('ids','P');
		$arrAttachmentid=explode(',',$arrAttachmentid);

		if(is_array($arrAttachmentid)){
			foreach($arrAttachmentid as $nAttachmentid){
				$this->delete_attachment($nAttachmentid);
			}
		}
			
		$this->S(Dyhb::L('批量删除附件成功','Controller/Attachment'));
	}

	protected function cache_site_(){
		// 更新附件专辑附件数量统计
		$nAttachmentcategoryid=intval($this->_nAttachmentcategoryid);

		if($nAttachmentcategoryid>0){
			$oAttachmentcategory=Dyhb::instance('AttachmentcategoryModel');
			$oAttachmentcategory->updateAttachmentnum($nAttachmentcategoryid);

			if($oAttachmentcategory->isError()){
				$this->E($oAttachmentcategory->getErrorMessage());
			}
		}
		
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('site');
	}

}
