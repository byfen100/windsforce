<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   添加好友($)*/

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
