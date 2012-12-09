<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   Flash上传附件信息处理($)*/

!defined('DYHB_PATH') && exit;

class FlashinfoController extends Controller{

	public function index(){
		$arrUploadids=G::getGpc('attachids','P');
		$nAttachmentcategoryid=intval(G::getGpc('attachmentcategory_id_flash'));
		$nDialog=intval(G::getGpc('dialog'));
		$sFunction=trim(G::getGpc('function'));

		$sHashcode=G::randString(32);
		Dyhb::cookie('_upload_hashcode_',$sHashcode,3600);

		$sUploadids=implode(',',$arrUploadids);

		if($nDialog==1){
			$this->U('home://attachment/attachmentinfo?id='.$sUploadids.'&hash='.$sHashcode.'&cid='.$nAttachmentcategoryid.'&dialog=1&functon='.$sFunction);
		}else{
			$this->U('home://attachment/attachmentinfo?id='.$sUploadids.'&hash='.$sHashcode.'&cid='.$nAttachmentcategoryid);
		}
	}

}
