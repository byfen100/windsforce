<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   删除活动控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DeleteController extends Controller{

	public function index(){
		$nEventid=intval(G::getGpc('id','G'));

		if(empty($nEventid)){
			$this->E(Dyhb::L('你没有指定活动ID','Controller'));
		}

		$oEvent=EventModel::F('event_status=1 AND event_id=?',$nEventid)->getOne();
		if(empty($oEvent['event_id'])){
			$this->E(Dyhb::L('你要删除的活动不存在','Controller'));
		}

		// 判断权限
		if(!Eventadmin_Extend::checkEvent($oEvent)){
			$this->E(Dyhb::L('你没有权限删除活动','Controller'));
		}

		// 备份数据
		$nBackid=$oEvent['event_id'];
		$nBackuserid=$oEvent['user_id'];
		$sBacktitle=$oEvent['event_title'];
		$sBackcontent=Core_Extend::subString($oEvent['event_content'],100,false,1,false);

		$oEvent->destroy();

		// 删除关联数据(评论 && 感兴趣和参加数据)
		$oEventcommentMeta=EventcommentModel::M();
		$oEventcommentMeta->deleteWhere(array('event_id'=>$nId));

		if($oEventcommentMeta->isError()){
			$this->E($oEventcommentMeta->getErrorMessage());
		}

		$oEventuserMeta=EventuserModel::M();
		$oEventuserMeta->deleteWhere(array('event_id'=>$nId));

		if($oEventuserMeta->isError()){
			$this->E($oEventuserMeta->getErrorMessage());
		}

		$oEventattentionuserMeta=EventattentionuserModel::M();
		$oEventattentionuserMeta->deleteWhere(array('event_id'=>$nId));

		if($oEventattentionuserMeta->isError()){
			$this->E($oEventattentionuserMeta->getErrorMessage());
		}

		// 发送提醒
		if($GLOBALS['___login___']['user_id']!=$nBackuserid){
			$sNoticetemplate='<div class="notice_deleteevent"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('删除了你的活动','Controller').'&nbsp;<a href="{@event_link}">'.$sBacktitle.'</a></span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{content_message}</span></div>&nbsp;'.Dyhb::L('如果你对该操作有任何疑问，可以联系相关人员咨询','Controller').'</div><div class="notice_action"><a href="{@event_link}">'.Dyhb::L('查看','Controller').'</a></div></div>';

			$arrNoticedata=array(
				'@space_link'=>'home://space@?id='.$GLOBALS['___login___']['user_id'],
				'user_name'=>$GLOBALS['___login___']['user_name'],
				'@event_link'=>'event://event/show?id='.$nBackid,
				'content_message'=>$sBackcontent,
			);

			try{
				Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$nBackuserid,'deleteevent',$nBackid);
			}catch(Exception $e){
				$this->E($e->getMessage());
			}
		}

		$this->S(Dyhb::L('活动删除成功','Controller'));
	}

}
