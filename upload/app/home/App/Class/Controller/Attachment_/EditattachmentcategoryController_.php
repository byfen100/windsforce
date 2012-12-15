<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   编辑专辑($)*/

!defined('DYHB_PATH') && exit;

class EditattachmentcategoryController extends Controller{

	public function index(){
		$nAttachmentcategoryid=intval(G::getGpc('id'));
		$nDialog=intval(G::getGpc('dialog','G'));
		$sFunction=trim(G::getGpc('function','G'));
		$nFiletype=intval(G::getGpc('filetype','G'));

		if(empty($nAttachmentcategoryid)){
			$this->E(Dyhb::L('你没有选择你要编辑的专辑','Controller/Attachment'));
		}

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nAttachmentcategoryid)->getOne();
		if(empty($oAttachmentcategory['attachmentcategory_id'])){
			$this->E(Dyhb::L('你要编辑的专辑不存在','Controller/Attachment'));
		}

		if($oAttachmentcategory['user_id']!=$GLOBALS['___login___']['user_id']){
			$this->E(Dyhb::L('你不能编辑别人的专辑','Controller/Attachment'));
		}

		$this->assign('oAttachmentcategory',$oAttachmentcategory);
		$this->assign('nDialog',$nDialog);
		$this->assign('sFunction',$sFunction);
		$this->assign('nFiletype',$nFiletype);

		if($nDialog==1){
			$this->display('attachment+dialogeditattachmentcategory');
		}else{
			$this->display('attachment+editattachmentcategory');
		}
	}

	public function save(){
		$nAttachmentcategoryid=intval(G::getGpc('attachmentcategory_id','G'));
		$sAttachmentcategoryname=trim(G::getGpc('attachmentcategory_name','G'));
		$sAttachmentcategorycompositor=intval(G::getGpc('attachmentcategory_compositor','G'));
		$sAttachmentcategorydescription=trim(G::getGpc('attachmentcategory_description','G'));

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nAttachmentcategoryid)->getOne();
		$oAttachmentcategory->attachmentcategory_name=$sAttachmentcategoryname;
		$oAttachmentcategory->attachmentcategory_compositor=$sAttachmentcategorycompositor;
		$oAttachmentcategory->attachmentcategory_description=$sAttachmentcategorydescription;
		$oAttachmentcategory->save(0,'update');

		if($oAttachmentcategory->isError()){
			$this->E($oAttachmentcategory->getErrorMessage());
		}

		$this->A($oAttachmentcategory->toArray(),Dyhb::L('更新专辑信息成功','Controller/Attachment'),1);
	}

	public function dialogsave(){
		$nAttachmentcategoryid=intval(G::getGpc('attachmentcategory_id','G'));
		$nDialog=intval(G::getGpc('dialog'));
		$sFunction=trim(G::getGpc('function'));
		$nFiletype=intval(G::getGpc('filetype','G'));

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nAttachmentcategoryid)->getOne();
		$oAttachmentcategory->save(0,'update');

		if($oAttachmentcategory->isError()){
			$this->E($oAttachmentcategory->getErrorMessage());
		}

		G::urlGoTo(Dyhb::U('home://attachment/my_attachmentcategory?dialog=1&function='.$sFunction.'&filetype='.$nFiletype),1,Dyhb::L('更新专辑信息成功','Controller/Attachment'));
	}

}
