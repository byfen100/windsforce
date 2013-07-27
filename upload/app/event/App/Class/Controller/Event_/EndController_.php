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

		// 发送feed
		$sFeedtemplate='<div class="feed_addevent"><span class="feed_title">'.Dyhb::L('提前结束了活动','Controller').'&nbsp;<a href="{@event_link}">'.$oEvent['event_title'].'</a></span><div class="feed_content">{event_content}</div><div class="feed_action"><a href="{@event_link}#comments">'.Dyhb::L('回复','Controller').'</a></div></div>';

		$arrFeeddata=array(
			'@event_link'=>'event://event/show?id='.$oEvent['event_id'],
			'event_content'=>Core_Extend::subString($oEvent['event_content'],100,false,1,false),
		);

		try{
			Core_Extend::addFeed($sFeedtemplate,$arrFeeddata);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		// 发送提醒
		if($GLOBALS['___login___']['user_id']!=$oEvent['user_id']){
			$sEventmessage=Core_Extend::subString($oEvent['event_content'],100,false,1,false);
			
			$sNoticetemplate='<div class="notice_endevent"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('提前结束了你的活动','Controller').'&nbsp;<a href="{@event_link}">'.$oEvent['event_title'].'</a></span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{content_message}</span></div>&nbsp;'.Dyhb::L('如果你对该操作有任何疑问，可以联系相关人员咨询','Controller').'</div><div class="notice_action"><a href="{@event_link}">'.Dyhb::L('查看','Controller').'</a></div></div>';

			$arrNoticedata=array(
				'@space_link'=>'home://space@?id='.$GLOBALS['___login___']['user_id'],
				'user_name'=>$GLOBALS['___login___']['user_name'],
				'@event_link'=>'event://event/show?id='.$oEvent['event_id'],
				'content_message'=>$sEventmessage,
			);

			try{
				Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$oEvent['user_id'],'endevent',$oEvent['event_id']);
			}catch(Exception $e){
				$this->E($e->getMessage());
			}
		}

		$this->S(Dyhb::L('提前结束活动成功','Controller'));
	}

}
