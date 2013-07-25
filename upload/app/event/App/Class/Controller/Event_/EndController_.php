<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   提前结束活动控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class EndController extends Controller{

	public function index(){
		$nEventid=intval(G::getGpc('id','G'));

		if(empty($nEventid)){
			$this->E(Dyhb::L('你没有指定活动ID','Controller'));
		}

		$oEvent=EventModel::F('event_status=1 AND event_id=?',$nEventid)->getOne();
		if(empty($oEvent['event_id'])){
			$this->E(Dyhb::L('你要提交结束的活动不存在','Controller'));
		}

		// 判断权限
		if(!Eventadmin_Extend::checkEvent($oEvent)){
			$this->E(Dyhb::L('你没有权限结束活动','Controller'));
		}

		// 判断活动是否已经结束
		if($oEvent['event_endtime']<CURRENT_TIMESTAMP){
			$this->E(Dyhb::L('活动已经结束','Controller'));
		}

		// 结束活动
		if($oEvent->event_starttime>=CURRENT_TIMESTAMP){
			$oEvent->event_endtime=$oEvent->event_starttime;
		}else{
			$oEvent->event_endtime=CURRENT_TIMESTAMP;
		}

		$oEvent->save(0,'update');

		if($oEvent->isError()){
			$this->E($oEvent->getErrorMessage());
		}

		$this->S(Dyhb::L('提前结束活动成功','Controller'));
	}

}
