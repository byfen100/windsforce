<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   对话框增加附件处理($)*/

!defined('DYHB_PATH') && exit;

class DialogaddattachmentcategoryController extends Controller{

	public function index(){
		$nDialog=intval(G::getGpc('dialog','G'));
		$sFunction=trim(G::getGpc('function','G'));

		$this->assign('nDialog',$nDialog);
		$this->assign('sFunction',$sFunction);
		
		$this->display('attachment+dialogaddattachmentcategory');
	}

	public function save(){
		$nDialog=intval(G::getGpc('dialog','P'));
		$sFunction=trim(G::getGpc('function','P'));

		$oAttachmentcategory=new AttachmentcategoryModel();
		$oAttachmentcategory->save(0);

		if($oAttachmentcategory->isError()){
			$this->E($oAttachmentcategory->getErrorMessage());
		}

		G::urlGoTo(Dyhb::U('home://attachment/my_attachmentcategory?dialog=1&function='.$sFunction),1,'专辑保存成功');
	}

}
