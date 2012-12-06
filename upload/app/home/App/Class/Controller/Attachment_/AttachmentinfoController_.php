<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   附件信息更新处理($)*/

!defined('DYHB_PATH') && exit;

class AttachmentinfoController extends Controller{

	public function index(){
		$sUploadids=trim(G::getGpc('id','G'));
		$sHashcode=trim(G::getGpc('hash','G'));
		$sCookieHashcode=Dyhb::cookie('_upload_hashcode_');
		$nAttachmentcategoryid=intval(G::getGpc('cid'));
		$nDialog=intval(G::getGpc('dialog'));
		$sFunction=trim(G::getGpc('function'));

		if(empty($sCookieHashcode)){
			$this->assign('__JumpUrl__',Dyhb::U('home://attachment/add'));
			$this->E('附件信息编辑页面已过期');
		}

		if($sCookieHashcode!=$sHashcode){
			$this->assign('__JumpUrl__',Dyhb::U('home://attachment/add'));
			$this->E('附件信息编辑页面Hash验证失败');
		}

		if(empty($sUploadids)){
			$this->assign('__JumpUrl__',Dyhb::U('home://attachment/add'));
			$this->E('你没有选择需要编辑的附件');
		}

		$arrAttachments=AttachmentModel::F('user_id=? AND attachment_id in('.$sUploadids.')',$GLOBALS['___login___']['user_id'])->getAll();

		$this->assign('arrAttachments',$arrAttachments);
		$this->assign('nAttachmentcategoryid',$nAttachmentcategoryid);
		$this->assign('nDialog',$nDialog);
		$this->assign('sFunction',$sFunction);

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

		$this->S('附件信息保存成功');
	}

}
