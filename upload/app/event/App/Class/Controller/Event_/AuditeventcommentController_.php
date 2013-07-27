<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动评论审核($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AuditeventcommentController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));
		$nStatus=intval(G::getGpc('status','G'));

		$oEventcomment=EventcommentModel::F('eventcomment_id=? AND eventcomment_status=1',$nId)->getOne();
		if(empty($oEventcomment['eventcomment_id'])){
			$this->E(Dyhb::L('待操作的评论不存在或者已被系统屏蔽','Controller'));
		}

		$oEventcomment->eventcomment_auditpass=$nStatus;
		$oEventcomment->save(0,'update');

		if($oEventcomment->isError()){
			$this->E($oEventcomment->getErrorMessage());
		}

		// 更新评论数量
		$oEvent=Dyhb::instance('EventModel');
		$oEvent->updateEventcommentnum($oEventcomment['event_id']);

		if($oEvent->isError()){
			$oEvent->getErrorMessage();
		}

		$this->S(Dyhb::L('评论操作成功','Controller'));
	}

}
