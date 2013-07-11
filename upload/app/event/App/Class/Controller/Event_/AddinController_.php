<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加活动入库控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AddinController extends Controller{

	public function index(){
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

		$this->S(Dyhb::L('活动添加成功','Controller'));
	}

}
