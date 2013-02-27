<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Flash上传附件信息处理($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class FlashinfoController extends Controller{

	public function index(){
		$arrUploadids=G::getGpc('attachids','P');
		$nAttachmentcategoryid=intval(G::getGpc('attachmentcategory_id_flash'));
		$nDialog=intval(G::getGpc('dialog'));
		$sFunction=trim(G::getGpc('function'));
		$nFiletype=intval(G::getGpc('filetype','P'));

		$sHashcode=G::randString(32);
		Dyhb::cookie('_upload_hashcode_',$sHashcode,3600);

		$sUploadids=implode(',',$arrUploadids);

		if($nDialog==1){
			$this->U('home://attachment/attachmentinfo?id='.$sUploadids.'&hash='.$sHashcode.'&cid='.$nAttachmentcategoryid.'&dialog=1&functon='.$sFunction.($nFiletype==1?'&filetype='.$nFiletype:''));
		}else{
			$this->U('home://attachment/attachmentinfo?id='.$sUploadids.'&hash='.$sHashcode.'&cid='.$nAttachmentcategoryid);
		}
	}

}
