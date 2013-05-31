<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   对话框增加附件处理($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DialogaddattachmentcategoryController extends Controller{

	public function index(){
		$nDialog=intval(G::getGpc('dialog','G'));
		$sFunction=trim(G::getGpc('function','G'));
		$nFiletype=intval(G::getGpc('filetype','G'));

		$this->assign('nDialog',$nDialog);
		$this->assign('sFunction',$sFunction);
		$this->assign('nFiletype',$nFiletype);
		
		$this->display('attachment+dialogaddattachmentcategory');
	}

	public function save(){
		$nDialog=intval(G::getGpc('dialog','P'));
		$sFunction=trim(G::getGpc('function','P'));
		$nFiletype=intval(G::getGpc('filetype','G'));
		$sAttachmentcategoryname=trim(G::getGpc('attachmentcategory_name'));

		if(!$sAttachmentcategoryname){
			G::urlGoTo(Dyhb::U('home://attachment/dialog_addattachmentcategory?dialog=1&function='.$sFunction.'&filetype='.$nFiletype),2,Dyhb::L('专辑名字不能为空','Controller'));
			exit();
		}

		$oAttachmentcategory=new AttachmentcategoryModel();
		$oAttachmentcategory->attachmentcategory_sort=intval(G::getGpc('attachementcategory_sort'));
		$oAttachmentcategory->attachmentcategory_name=$sAttachmentcategoryname;
		$oAttachmentcategory->save(0);

		if($oAttachmentcategory->isError()){
			$this->E($oAttachmentcategory->getErrorMessage());
		}

		G::urlGoTo(Dyhb::U('home://attachment/my_attachmentcategory?dialog=1&function='.$sFunction.'&filetype='.$nFiletype),1,Dyhb::L('专辑保存成功','Controller'));
	}

}
