<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   推荐和取消附件($)*/

!defined('DYHB_PATH') && exit;

class RecommendattachmentController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));

		$oAttachment=AttachmentModel::F('attachment_id=?',$nId)->getOne();
		if(empty($oAttachment['attachment_id'])){
			$this->E('你推荐的附件不存在');
		}

		$oAttachment->attachment_recommend=1;
		$oAttachment->save(0,'update');

		if($oAttachment->isError()){
			$this->E($oAttachment->getErrorMessage());
		}

		$this->S('附件推荐成功');
	}

	public function un(){
		$nId=intval(G::getGpc('id','G'));

		$oAttachment=AttachmentModel::F('attachment_id=?',$nId)->getOne();
		if(empty($oAttachment['attachment_id'])){
			$this->E('你推荐的附件不存在');
		}

		$oAttachment->attachment_recommend=0;
		$oAttachment->save(0,'update');

		if($oAttachment->isError()){
			$this->E($oAttachment->getErrorMessage());
		}

		$this->S('附件取消推荐成功');
	}

}
