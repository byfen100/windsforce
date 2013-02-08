<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   推荐和取消专辑($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class RecommendattachmentcategoryController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nId)->getOne();
		if(empty($oAttachmentcategory['attachmentcategory_id'])){
			$this->E(Dyhb::L('你推荐的专辑不存在','Controller/Attachment'));
		}

		$oAttachmentcategory->attachmentcategory_recommend=1;
		$oAttachmentcategory->save(0,'update');

		if($oAttachmentcategory->isError()){
			$this->E($oAttachmentcategory->getErrorMessage());
		}

		$this->S(Dyhb::L('专辑推荐成功','Controller/Attachment'));
	}

	public function un(){
		$nId=intval(G::getGpc('id','G'));

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nId)->getOne();
		if(empty($oAttachmentcategory['attachmentcategory_id'])){
			$this->E(Dyhb::L('你推荐的专辑不存在','Controller/Attachment'));
		}

		$oAttachmentcategory->attachmentcategory_recommend=0;
		$oAttachmentcategory->save(0,'update');

		if($oAttachmentcategory->isError()){
			$this->E($oAttachmentcategory->getErrorMessage());
		}

		$this->S(Dyhb::L('专辑取消推荐成功','Controller/Attachment'));
	}

}
