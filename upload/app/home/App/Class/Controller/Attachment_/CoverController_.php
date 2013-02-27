<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   照片封面操作($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CoverController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));

		if(empty($nId)){
			$this->E(Dyhb::L('没有待设置的照片ID','Controller/Attachment'));
		}

		$oAttachment=AttachmentModel::F('attachment_id=?',$nId)->getOne();
		if(!empty($oAttachment['attachment_id'])){
			if($oAttachment['attachmentcategory_id']>0){
				$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$oAttachment['attachmentcategory_id'])->getOne();

				if(empty($oAttachmentcategory['attachmentcategory_id'])){
					$this->E(Dyhb::L('照片的专辑不存在','Controller/Attachment'));
				}

				$oAttachmentcategory->attachmentcategory_cover=$nId;
				$oAttachmentcategory->save(0,'update');

				if($oAttachmentcategory->isError()){
					$this->E($oAttachmentcategory->getErrorMessage());
				}

				$this->S(Dyhb::L('专辑封面设置成功','Controller/Attachment'));
			}else{
				$this->E(Dyhb::L('默认专辑不需要设置封面','Controller/Attachment'));
			}
		}else{
			$this->E(Dyhb::L('待设置的照片不存在','Controller/Attachment'));
		}
	}

}
