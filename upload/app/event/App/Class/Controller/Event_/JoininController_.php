<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   参加活动处理控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class JoininController extends Controller{

	public function index(){
		$nEventid=intval(G::getGpc('event_id'));
		$sEventusercontact=G::text(trim(G::getGpc('eventuser_contact')));

		if(empty($nEventid)){
			$this->E(Dyhb::L('你没有指定活动ID','Controller'));
		}

		$oEvent=EventModel::F('event_status=1 AND event_id=?',$nEventid)->getOne();
		if(empty($oEvent['event_id'])){
			$this->E(Dyhb::L('你要浏览的活动不存在','Controller'));
		}

		// 判断是否已经参加活动
		$oEventuser=EventuserModel::F('event_id=? AND user_id=?',$nEventid,$GLOBALS['___login___']['user_id'])->getOne();

		if(!empty($oEventuser['user_id'])){
			$this->E(Dyhb::L('你已经参加过该活动或者你是活动发起人','Controller'));
		}

		// 写入参加活动
		$oEventuser=new EventuserModel();
		$oEventuser->event_id=$nEventid;
		$oEventuser->eventuser_contact=$sEventusercontact;
		
		if($oEvent['event_isaudit']==1){
			$oEventuser->eventuser_status='0';
		}

		$oEventuser->save(0);

		if($oEventuser->isError()){
			$this->E($oEventuser->getErrorMessage());
		}

		// 更新活动参加人数
		$oEventTemp=Dyhb::instance('EventModel');
		$oEventTemp->updateEventjoinnum($nEventid);

		if($oEventTemp->isError()){
			$oEventTemp->getErrorMessage();
		}

		$this->S(Dyhb::L('参加活动成功','Controller'));
	}

}
