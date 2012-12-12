<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   编辑附件($)*/

!defined('DYHB_PATH') && exit;

class EditattachmentController extends Controller{

	public function index(){
		$nAttachmentid=intval(G::getGpc('id'));

		if(empty($nAttachmentid)){
			$this->E(Dyhb::L('你没有选择你要编辑的附件','Controller/Attachment'));
		}

		$oAttachment=AttachmentModel::F('attachment_id=?',$nAttachmentid)->getOne();
		if(empty($oAttachment['attachment_id'])){
			$this->E(Dyhb::L('你要编辑的附件不存在','Controller/Attachment'));
		}

		if($oAttachment['user_id']!=$GLOBALS['___login___']['user_id']){
			$this->E(Dyhb::L('你不能编辑别人的附件','Controller/Attachment'));
		}

		$this->assign('oAttachment',$oAttachment);

		$this->display('attachment+editattachment');
	}

	public function save(){
		$nAttachmentid=intval(G::getGpc('attachment_id','G'));
		$sAttachmentname=trim(G::getGpc('attachment_name','G'));
		$sAttachmentalt=trim(G::getGpc('attachment_alt','G'));
		$sAttachmentdescription=trim(G::getGpc('attachment_description','G'));

		$oAttachment=AttachmentModel::F('attachment_id=?',$nAttachmentid)->getOne();
		$oAttachment->attachment_name=$sAttachmentname;
		$oAttachment->attachment_alt=$sAttachmentalt;
		$oAttachment->attachment_description=$sAttachmentdescription;
		$oAttachment->save(0,'update');

		if($oAttachment->isError()){
			$this->E($oAttachment->getErrorMessage());
		}

		$this->A($oAttachment->toArray(),Dyhb::L('更新附件信息成功','Controller/Attachment'),1);
	}

}
