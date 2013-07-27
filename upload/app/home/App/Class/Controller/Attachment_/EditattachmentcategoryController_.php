<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   编辑专辑($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class EditattachmentcategoryController extends Controller{

	public function index(){
		$nAttachmentcategoryid=intval(G::getGpc('id'));
		$nDialog=intval(G::getGpc('dialog','G'));
		$sFunction=trim(G::getGpc('function','G'));
		$nFiletype=intval(G::getGpc('filetype','G'));

		if(empty($nAttachmentcategoryid)){
			$this->E(Dyhb::L('你没有选择你要编辑的专辑','Controller'));
		}

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nAttachmentcategoryid)->getOne();
		if(empty($oAttachmentcategory['attachmentcategory_id'])){
			$this->E(Dyhb::L('你要编辑的专辑不存在','Controller'));
		}

		if($oAttachmentcategory['user_id']!=$GLOBALS['___login___']['user_id']){
			$this->E(Dyhb::L('你不能编辑别人的专辑','Controller'));
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
		$sAttachmentcategorysort=intval(G::getGpc('attachmentcategory_sort','G'));
		$sAttachmentcategorydescription=trim(G::getGpc('attachmentcategory_description','G'));
		$sAttachmentcategorycover=trim(G::getGpc('attachmentcategory_cover','G'));

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nAttachmentcategoryid)->getOne();
		$oAttachmentcategory->attachmentcategory_name=$sAttachmentcategoryname;
		$oAttachmentcategory->attachmentcategory_sort=$sAttachmentcategorysort;
		$oAttachmentcategory->attachmentcategory_description=$sAttachmentcategorydescription;
		$oAttachmentcategory->attachmentcategory_cover=$sAttachmentcategorycover;
		$oAttachmentcategory->save(0,'update');

		if($oAttachmentcategory->isError()){
			$this->E($oAttachmentcategory->getErrorMessage());
		}

		$this->A($oAttachmentcategory->toArray(),Dyhb::L('更新专辑信息成功','Controller'),1);
	}

	public function dialogsave(){
		$nAttachmentcategoryid=intval(G::getGpc('attachmentcategory_id'));
		$nDialog=intval(G::getGpc('dialog'));
		$sFunction=trim(G::getGpc('function'));
		$nFiletype=intval(G::getGpc('filetype','G'));
		$sAttachmentcategoryname=trim(G::getGpc('attachmentcategory_name'));

		if(!$sAttachmentcategoryname){
			G::urlGoTo(Dyhb::U('home://attachment/edit_attachmentcategory?id='.$nAttachmentcategoryid.'&dialog=1&function='.$sFunction.'&filetype='.$nFiletype),2,Dyhb::L('专辑名字不能为空','Controller'));
			exit();
		}

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nAttachmentcategoryid)->getOne();
		$oAttachmentcategory->attachmentcategory_sort=intval(G::getGpc('attachementcategory_sort'));
		$oAttachmentcategory->attachmentcategory_name=$sAttachmentcategoryname;
		$oAttachmentcategory->save(0,'update');

		if($oAttachmentcategory->isError()){
			$this->E($oAttachmentcategory->getErrorMessage());
		}

		G::urlGoTo(Dyhb::U('home://attachment/my_attachmentcategory?dialog=1&function='.$sFunction.'&filetype='.$nFiletype),1,Dyhb::L('更新专辑信息成功','Controller'));
	}

}
