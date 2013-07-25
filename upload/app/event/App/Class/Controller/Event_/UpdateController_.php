<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   更新活动控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UpdateController extends Controller{

	public function index(){
		// 活动时间先验证
		$nEventstarttime=strtotime(trim(G::getGpc('event_starttime','P')));
		$nEventendtime=strtotime(trim(G::getGpc('event_endtime','P')));
		$nEventdeadline=strtotime(trim(G::getGpc('event_deadline','P')));

		if(!$nEventstarttime){
			$this->E(Dyhb::L('活动开始时间不能为空','__APPEVENT_COMMON_LANG__@Model'));
		}

		if(!$nEventendtime){
			$this->E(Dyhb::L('活动结束时间不能为空','__APPEVENT_COMMON_LANG__@Model'));
		}

		if(!$nEventdeadline){
			$this->E(Dyhb::L('活动报名截止时间不能为空','__APPEVENT_COMMON_LANG__@Model'));
		}

		if($nEventstarttime>$nEventendtime){
			$this->E(Dyhb::L('活动结束时间不能早于活动开始时间','__APPEVENT_COMMON_LANG__@Model'));
		}
		
		if($nEventdeadline<CURRENT_TIMESTAMP){
			$this->E(Dyhb::L('活动报名时间不能早于当前时间','__APPEVENT_COMMON_LANG__@Model'));
		}
		
		if($nEventdeadline>$nEventendtime){
			$this->E(Dyhb::L('活动报名时间不能晚于活动结束时间','__APPEVENT_COMMON_LANG__@Model'));
		}
		
		// 更新活动
		$nEventid=intval(G::getGpc('event_id'));

		if(empty($nEventid)){
			$this->E(Dyhb::L('你没有指定活动ID','Controller'));
		}

		$oEvent=EventModel::F('event_status=1 AND event_id=?',$nEventid)->getOne();
		if(empty($oEvent['event_id'])){
			$this->E(Dyhb::L('你要更新的活动不存在','Controller'));
		}

		// 判断权限
		if(!Eventadmin_Extend::checkEvent($oEvent)){
			$this->E(Dyhb::L('你没有权限编辑活动','Controller'));
		}

		$_POST['event_starttime']=$nEventstarttime;
		$_POST['event_endtime']=$nEventendtime;
		$_POST['event_deadline']=$nEventdeadline;

		$oEvent->save(0,'update');

		if($oEvent->isError()){
			$this->E($oEvent->getErrorMessage());
		}

		$arrData=$oEvent->toArray();
		$arrData['url']=Dyhb::U('event://e@?id='.$oEvent['event_id']);

		$this->A($arrData,Dyhb::L('活动更新成功','Controller'));
	}

}
