<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   删除专辑封面操作($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UncoverController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));

		if(empty($nId)){
			$this->E(Dyhb::L('没有待取消封面的专辑ID','Controller/Attachment'));
		}

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nId)->getOne();
		if(empty($oAttachmentcategory['attachmentcategory_id'])){
			$this->E(Dyhb::L('待取消封面的专辑不存在','Controller/Attachment'));
		}

		$oAttachmentcategory->attachmentcategory_cover='0';
		$oAttachmentcategory->save(0,'update');

		if($oAttachmentcategory->isError()){
			$this->E($oAttachmentcategory->getErrorMessage());
		}

		$this->S(Dyhb::L('专辑封面删除成功','Controller/Attachment'));
	}

}
