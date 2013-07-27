<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加活动入库控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AddinController extends Controller{

	public function index(){
		if($GLOBALS['_cache_']['event_option']['front_add']==0){
			$this->E(Dyhb::L('系统关闭了创建活动功能','Controller'));
		}
		
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
		
		if($GLOBALS['_cache_']['event_option']['event_audit']==1){
			$oEvent->event_status='0';
		}
		
		$oEvent->save(0);

		if($oEvent->isError()){
			$this->E($oEvent->getErrorMessage());
		}

		// 发送feed
		$sFeedtemplate='<div class="feed_addevent"><span class="feed_title">'.Dyhb::L('发布了一个活动','Controller').'&nbsp;<a href="{@event_link}">'.$oEvent['event_title'].'</a></span><div class="feed_content">{event_content}</div><div class="feed_action"><a href="{@event_link}#comments">'.Dyhb::L('回复','Controller').'</a></div></div>';

		$arrFeeddata=array(
			'@event_link'=>'event://event/show?id='.$oEvent['event_id'],
			'event_content'=>Core_Extend::subString($oEvent['event_content'],100,false,1,false),
		);

		try{
			Core_Extend::addFeed($sFeedtemplate,$arrFeeddata);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		// 后续处理
		$arrData=$oEvent->toArray();

		if($oEvent['event_status']==1){
			$arrData['url']=Dyhb::U('event://e@?id='.$oEvent['event_id']);
		}else{
			$arrData['url']=Dyhb::U('event://ucenter/index');
		}

		$this->A($arrData,Dyhb::L('活动添加成功','Controller'));
	}

}
