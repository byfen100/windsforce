<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   删除附件专辑($)*/

!defined('DYHB_PATH') && exit;

class DeleteattachmentcategoryController extends Controller{

	public function index($nId=''){
		if(empty($nId)){
			$nAttachmentcategoryid=intval(G::getGpc('id','G'));
		}else{
			$nAttachmentcategoryid=$nId;
		}

		if(empty($nAttachmentcategoryid)){
			$this->E('你没有选择你要删除的专辑');
		}

		$oAttachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=?',$nAttachmentcategoryid)->getOne();
		if(empty($oAttachmentcategory['attachmentcategory_id'])){
			$this->E('你要删除的专辑不存在');
		}

		if($oAttachmentcategory['user_id']!=$GLOBALS['___login___']['user_id']){
			$this->E('你不能删除别人的专辑');
		}

		$nTotalRecord=AttachmentModel::F('attachmentcategory_id=?',$oAttachmentcategory['attachmentcategory_id'])->all()->getCounts();
		if($nTotalRecord>0){
			$this->E('专辑含有照片，请先删除照片后再删除专辑');
		}

		$oAttachmentcategory->destroy();

		if(!$nId){
			$this->S('专辑删除成功');
		}
	}

}
