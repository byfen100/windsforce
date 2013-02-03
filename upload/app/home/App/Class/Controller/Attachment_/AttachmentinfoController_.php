<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   附件信息更新处理($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AttachmentinfoController extends Controller{

	public function index(){
		$sUploadids=trim(G::getGpc('id','G'));
		$sHashcode=trim(G::getGpc('hash','G'));
		$sCookieHashcode=Dyhb::cookie('_upload_hashcode_');
		$nAttachmentcategoryid=intval(G::getGpc('cid'));
		$nDialog=intval(G::getGpc('dialog'));
		$sFunction=trim(G::getGpc('function'));
		$nFiletype=intval(G::getGpc('filetype'));

		if(empty($sCookieHashcode)){
			$this->assign('__JumpUrl__',Dyhb::U('home://attachment/add'));
			$this->E(Dyhb::L('附件信息编辑页面已过期','Controller/Attachment'));
		}

		if($sCookieHashcode!=$sHashcode){
			$this->assign('__JumpUrl__',Dyhb::U('home://attachment/add'));
			$this->E(Dyhb::L('附件信息编辑页面Hash验证失败','Controller/Attachment'));
		}

		if(empty($sUploadids)){
			$this->assign('__JumpUrl__',Dyhb::U('home://attachment/add'));
			$this->E(Dyhb::L('你没有选择需要编辑的附件','Controller/Attachment'));
		}

		$arrAttachments=AttachmentModel::F('user_id=? AND attachment_id in('.$sUploadids.')',$GLOBALS['___login___']['user_id'])->getAll();

		$this->assign('arrAttachments',$arrAttachments);
		$this->assign('nAttachmentcategoryid',$nAttachmentcategoryid);
		$this->assign('nDialog',$nDialog);
		$this->assign('sFunction',$sFunction);
		$this->assign('nFiletype',$nFiletype);

		if($nDialog==1){
			$this->display('attachment+dialogattachmentinfo');
		}else{
			$this->display('attachment+attachmentinfo');
		}
	}

	public function save(){
		$arrAttachments=G::getGpc('attachments','P');
		$nAttachmentcategoryid=intval(G::getGpc('attachmentcategory_id'));

		if(is_array($arrAttachments)){
			foreach($arrAttachments as $nKey=>$arrAttachment){
				$oAttachment=AttachmentModel::F('attachment_id=?',$nKey)->getOne();
				if(!empty($oAttachment['attachment_id'])){
					$oAttachment->changeProp($arrAttachment);
					$oAttachment->save(0,'update');

					if($oAttachment->isError()){
						$this->E($oAttachment->getErrorMessage());
					}
				}
			}
		}

		Dyhb::cookie('_upload_hashcode_',null,-1);

		$this->S(Dyhb::L('附件信息保存成功','Controller/Attachment'));
	}

	public function attachmentinfo_title_(){
		return Dyhb::L('保存附件信息','Controller/Attachment');
	}

	public function attachmentinfo_keywords_(){
		return $this->attachmentinfo_title_();
	}

	public function attachmentinfo_description_(){
		return $this->attachmentinfo_title_();
	}

}
