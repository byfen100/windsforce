<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动成员审核($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AuditController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));
		$nUserid=intval(G::getGpc('uid','G'));

		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定活动ID','Controller'));
		}

		$oEvent=EventModel::F('event_status=1 AND event_id=?',$nId)->getOne();
		if(empty($oEvent['event_id'])){
			$this->E(Dyhb::L('你要浏览的活动不存在','Controller'));
		}

		// 处理审核数据
		$oEventuser=EventuserModel::F('event_id=? AND user_id=? AND eventuser_status=0',$nId,$nUserid)->getOne();

		if(empty($oEventuser['event_id'])){
			$this->E(Dyhb::L('没有带审核的用户或者你已经通过了审核','Controller'));
		}

		$oEventuser->eventuser_status='1';
		$oEventuser->save(0,'update');

		if($oEventuser->isError()){
			$this->E($oEventuser->getErrorMessage());
		}

		$this->S(Dyhb::L('活动成员审核成功','Controller'));
	}

}
