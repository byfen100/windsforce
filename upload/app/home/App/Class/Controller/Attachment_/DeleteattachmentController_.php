<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   删除附件操作($)*/

!defined('DYHB_PATH') && exit;

class DeleteattachmentController extends Controller{

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

}
