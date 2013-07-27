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

		// 发送feed
		$sFeedtemplate='<div class="feed_addevent"><span class="feed_title">'.Dyhb::L('编辑了活动','Controller').'&nbsp;<a href="{@event_link}">'.$oEvent['event_title'].'</a></span><div class="feed_content">{event_content}</div><div class="feed_action"><a href="{@event_link}#comments">'.Dyhb::L('回复','Controller').'</a></div></div>';

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
			
			$sNoticetemplate='<div class="notice_editevent"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('编辑了你的活动','Controller').'&nbsp;<a href="{@event_link}">'.$oEvent['event_title'].'</a></span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{content_message}</span></div>&nbsp;'.Dyhb::L('如果你对该操作有任何疑问，可以联系相关人员咨询','Controller').'</div><div class="notice_action"><a href="{@event_link}">'.Dyhb::L('查看','Controller').'</a></div></div>';

			$arrNoticedata=array(
				'@space_link'=>'home://space@?id='.$GLOBALS['___login___']['user_id'],
				'user_name'=>$GLOBALS['___login___']['user_name'],
				'@event_link'=>'event://event/show?id='.$oEvent['event_id'],
				'content_message'=>$sEventmessage,
			);

			try{
				Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$oEvent['user_id'],'editevent',$oEvent['event_id']);
			}catch(Exception $e){
				$this->E($e->getMessage());
			}
		}

		// 后续处理
		$arrData=$oEvent->toArray();
		$arrData['url']=Dyhb::U('event://e@?id='.$oEvent['event_id']);

		$this->A($arrData,Dyhb::L('活动更新成功','Controller'));
	}

}
