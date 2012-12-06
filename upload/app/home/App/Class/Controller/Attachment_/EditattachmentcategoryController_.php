<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   编辑专辑($)*/

!defined('DYHB_PATH') && exit;

class EditattachmentcategoryController extends Controller{

	public function index(){
		$nAttachmentcategoryid=intval(G::getGpc('id'));
		$nDialog=intval(G::getGpc('dialog','G'));
		$sFunction=trim(G::getGpc('function','G'));

		if(empty($nAttachmentcategoryid)){
			$this->E('你没有选择你要编辑的专辑');
		}

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nAttachmentcategoryid)->getOne();
		if(empty($oAttachmentcategory['attachmentcategory_id'])){
			$this->E('你要编辑的专辑不存在');
		}

		if($oAttachmentcategory['user_id']!=$GLOBALS['___login___']['user_id']){
			$this->E('你不能编辑别人的专辑');
		}

		$this->assign('oAttachmentcategory',$oAttachmentcategory);
		$this->assign('nDialog',$nDialog);
		$this->assign('sFunction',$sFunction);

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

		$this->A($oAttachmentcategory->toArray(),'更新专辑信息成功',1);
	}

	public function dialogsave(){
		$nAttachmentcategoryid=intval(G::getGpc('attachmentcategory_id','G'));
		$nDialog=intval(G::getGpc('dialog'));
		$sFunction=trim(G::getGpc('function'));

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nAttachmentcategoryid)->getOne();
		$oAttachmentcategory->save(0,'update');

		if($oAttachmentcategory->isError()){
			$this->E($oAttachmentcategory->getErrorMessage());
		}

		G::urlGoTo(Dyhb::U('home://attachment/my_attachmentcategory?dialog=1&function='.$sFunction),1,'更新专辑信息成功');
	}

}
