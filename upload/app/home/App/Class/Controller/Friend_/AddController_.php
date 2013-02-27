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
			$this->S(Dyhb::L('添加好友成功','Controller/Friend'));
		}
	}

}
