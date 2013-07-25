<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   参加活动确认框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class JoinController extends Controller{

	public function index(){
		$nEventid=intval(G::getGpc('id','G'));

		if(empty($nEventid)){
			$this->E(Dyhb::L('你没有指定活动ID','Controller'));
		}

		$oEvent=EventModel::F('event_status=1 AND event_id=?',$nEventid)->getOne();
		if(empty($oEvent['event_id'])){
			$this->E(Dyhb::L('你要浏览的活动不存在','Controller'));
		}

		// 判断是否已经参加活动
		$oEventuser=EventuserModel::F('event_id=? AND user_id=?',$nEventid,$GLOBALS['___login___']['user_id'])->getOne();

		if($GLOBALS['___login___']['user_id']==$oEvent['user_id'] || !empty($oEventuser['user_id'])){
			$this->E(Dyhb::L('你已经参加过该活动或者你是活动发起人','Controller'));
		}

		// 判断活动是否已经结束
		if($oEvent['event_endtime']<CURRENT_TIMESTAMP){
			$this->E(Dyhb::L('对不起，活动已经结束','Controller'));
		}

		$this->assign('oEvent',$oEvent);

		$this->display('event+join');
	}

}
