<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   删除好友($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DeleteController extends Controller{

	public function index(){
		$nFriendId=intval(G::getGpc('friendid'));
		$nFan=intval(G::getGpc('fan'));
		
		$oFriendModel=Dyhb::instance('FriendModel');
		$oFriendModel->deleteFriend($nFriendId,$GLOBALS['___login___']['user_id'],$nFan);
		
		if($oFriendModel->isError()){
			$this->E($oFriendModel->getErrorMessage());
		}else{
			$this->S(Dyhb::L('删除好友成功','Controller'));
		}
	}

}
