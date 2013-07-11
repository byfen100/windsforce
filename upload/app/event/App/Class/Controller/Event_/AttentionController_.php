<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   感兴趣活动处理控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AttentionController extends Controller{

	public function index(){
		$nEventid=intval(G::getGpc('id'));

		if(empty($nEventid)){
			$this->E(Dyhb::L('你没有指定活动ID','Controller'));
		}

		$oEvent=EventModel::F('event_status=1 AND event_id=?',$nEventid)->getOne();
		if(empty($oEvent['event_id'])){
			$this->E(Dyhb::L('你要浏览的活动不存在','Controller'));
		}

		// 判断是否已经参加过活动了
		$oEventattentionuser=EventattentionuserModel('event_id=? AND user_id=?',$nEventid,$GLOBALS['___login___']['user_id'])->getOne();

		if($oEventattentionuser['event_id']){
			$this->E(Dyhb::L('你已经对这个应用感兴趣过了','Controller'));
		}

		// 写入活动
		$oEventattentionuser=new EventattentionuserModel();
		$oEventattentionuser->event_id=$nEventid;
		$oEventattentionuser->save(0);

		if($oEventattentionuser->isError()){
			$this->E($oEventattentionuser->getErrorMessage());
		}

		$this->S(Dyhb::L('添加感兴趣活动成功','Controller'));
	}

}
