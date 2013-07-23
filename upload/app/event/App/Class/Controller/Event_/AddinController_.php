<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加活动入库控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AddinController extends Controller{

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
		
		// 保存活动
		$oEvent=new EventModel();
		$oEvent->formatTime();
		$oEvent->save(0);

		if($oEvent->isError()){
			$this->E($oEvent->getErrorMessage());
		}

		// 保存活动发起人
		$oEventuser=new EventuserModel();
		$oEventuser->event_id=$oEvent['event_id'];
		$oEventuser->eventuser_contact=$sEventusercontact;
		$oEventuser->eventuser_admin=1;
		$oEventuser->save(0);

		if($oEventuser->isError()){
			$this->E($oEventuser->getErrorMessage());
		}

		$arrData=$oEvent->toArray();
		$arrData['url']=Dyhb::U('event://e@?id='.$oEvent['event_id']);

		$this->A($arrData,Dyhb::L('活动添加成功','Controller'));
	}

}
