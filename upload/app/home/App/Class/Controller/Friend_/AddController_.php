<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加好友($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AddController extends Controller{

	public function index(){
		$nUserId=intval(G::getGpc('uid'));
		
		$oFriendModel=Dyhb::instance('FriendModel');
		$oFriendModel->addFriend($nUserId,$GLOBALS['___login___']['user_id']);
		
		if($oFriendModel->isError()){
			$this->E($oFriendModel->getErrorMessage());
		}else{
			// 发送提醒
			$sNoticetemplate='<div class="notice_credit"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('成为了你的粉丝','Controller').'</span><div class="notice_action"><a href="{@fan_link}">'.Dyhb::L('查看','Controller').'</a></div></div>';

			$arrNoticedata=array(
				'@space_link'=>'home://space@?id='.$GLOBALS['___login___']['user_id'],
				'user_name'=>$GLOBALS['___login___']['user_name'],
				'@fan_link'=>'home://friend/index?type=fan',
			);

			try{
				Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$nUserId,'friend');
			}catch(Exception $e){
				$this->E($e->getMessage());
			}

			$this->S(Dyhb::L('添加好友成功','Controller'));
		}
	}

}
