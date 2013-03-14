<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   附件评论审核($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AuditattachmentcommentController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));
		$nStatus=intval(G::getGpc('status','G'));

		$oAttachmentcomment=AttachmentcommentModel::F('attachmentcomment_id=? AND attachmentcomment_status=1',$nId)->getOne();
		if(empty($oAttachmentcomment['attachmentcomment_id'])){
			$this->E(Dyhb::L('待操作的评论不存在或者已被系统屏蔽','Controller/Attachment'));
		}

		$oAttachmentcomment->attachmentcomment_auditpass=$nStatus;
		$oAttachmentcomment->save(0,'update');

		if($oAttachmentcomment->isError()){
			$this->E($oAttachmentcomment->getErrorMessage());
		}

		// 更新评论数量
		$oAttachment=Dyhb::instance('AttachmentModel');
		$oAttachment->updateAttachmentcommentnum($oAttachmentcomment['attachment_id']);

		if($oAttachment->isError()){
			$oAttachment->getErrorMessage();
		}

		$this->S(Dyhb::L('评论操作成功','Controller/Attachment'));
	}

}
