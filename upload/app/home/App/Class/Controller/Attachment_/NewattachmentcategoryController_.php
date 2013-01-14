<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   创建新的专辑处理($)*/

!defined('DYHB_PATH') && exit;

class NewattachmentcategoryController extends Controller{

	public function index(){
		$this->display('attachment+newattachmentcategory');
	}

	public function save(){
		$nAttachmentcategorySort=intval(G::getGpc('attachmentcategory_sort','G'));
		$sAttachmentcategoryName=trim(G::getGpc('attachmentcategory_name','G'));
		$sAttachmentcategoryDescription=trim(G::getGpc('attachmentcategory_description','G'));

		$oAttachmentcategory=new AttachmentcategoryModel();
		$oAttachmentcategory->attachmentcategory_sort=$nAttachmentcategorySort;
		$oAttachmentcategory->attachmentcategory_name=$sAttachmentcategoryName;
		$oAttachmentcategory->attachmentcategory_description=$sAttachmentcategoryDescription;
		$oAttachmentcategory->save(0);

		if($oAttachmentcategory->isError()){
			$this->E($oAttachmentcategory->getErrorMessage());
		}

		$this->A($oAttachmentcategory->toArray(),Dyhb::L('新增专辑成功','Controller/Attachment'),1);
	}

}
