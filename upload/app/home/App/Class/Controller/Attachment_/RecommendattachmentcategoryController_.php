<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   推荐和取消专辑($)*/

!defined('DYHB_PATH') && exit;

class RecommendattachmentcategoryController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nId)->getOne();
		if(empty($oAttachmentcategory['attachmentcategory_id'])){
			$this->E('你推荐的专辑不存在');
		}

		$oAttachmentcategory->attachmentcategory_recommend=1;
		$oAttachmentcategory->save(0,'update');

		if($oAttachmentcategory->isError()){
			$this->E($oAttachmentcategory->getErrorMessage());
		}

		$this->S('专辑推荐成功');
	}

	public function un(){
		$nId=intval(G::getGpc('id','G'));

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nId)->getOne();
		if(empty($oAttachmentcategory['attachmentcategory_id'])){
			$this->E('你推荐的专辑不存在');
		}

		$oAttachmentcategory->attachmentcategory_recommend=0;
		$oAttachmentcategory->save(0,'update');

		if($oAttachmentcategory->isError()){
			$this->E($oAttachmentcategory->getErrorMessage());
		}

		$this->S('专辑取消推荐成功');
	}

}
