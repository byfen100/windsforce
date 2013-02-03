<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   新鲜事审核($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AuditController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));
		$nStatus=intval(G::getGpc('status','G'));

		$oHomefreshcomment=HomefreshcommentModel::F('homefreshcomment_id=? AND homefreshcomment_status=1',$nId)->getOne();
		if(empty($oHomefreshcomment['homefreshcomment_id'])){
			$this->E(Dyhb::L('待操作的评论不存在或者已被系统屏蔽','Controller/Homefresh'));
		}

		$oHomefreshcomment->homefreshcomment_auditpass=$nStatus;
		$oHomefreshcomment->save(0,'update');

		if($oHomefreshcomment->isError()){
			$this->E($oHomefreshcomment->getErrorMessage());
		}

		// 更新评论数量
		$oHomefresh=Dyhb::instance('HomefreshModel');
		$oHomefresh->updateHomefreshcommentnum($oHomefreshcomment['homefresh_id']);

		if($oHomefresh->isError()){
			$oHomefresh->getErrorMessage();
		}

		$this->S(Dyhb::L('评论操作成功','Controller/Homefresh'));
	}

}
