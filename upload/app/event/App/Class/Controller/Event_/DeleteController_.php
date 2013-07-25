<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   删除活动控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DeleteController extends Controller{

	public function index(){
		$nEventid=intval(G::getGpc('id','G'));

		if(empty($nEventid)){
			$this->E(Dyhb::L('你没有指定活动ID','Controller'));
		}

		$oEvent=EventModel::F('event_status=1 AND event_id=?',$nEventid)->getOne();
		if(empty($oEvent['event_id'])){
			$this->E(Dyhb::L('你要删除的活动不存在','Controller'));
		}

		// 判断权限
		if(!Eventadmin_Extend::checkEvent($oEvent)){
			$this->E(Dyhb::L('你没有权限删除活动','Controller'));
		}

		$oEvent->destroy();

		$this->S(Dyhb::L('活动删除成功','Controller'));
	}

}
