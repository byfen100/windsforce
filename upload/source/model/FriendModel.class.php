<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   好友系统模型($)*/

!defined('DYHB_PATH') && exit;

class FriendModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'friend',
			'props'=>array(
				'friend_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'friend_id',
			'check'=>array(
				'friend_comment'=>array(
					array('empty'),
					array('max_length',255,Dyhb::L('好友注释的字符数最多为255','__COMMON_LANG__@Model/Friend')),
				),
				'friend_fancomment'=>array(
					array('empty'),
					array('max_length',255,Dyhb::L('粉丝注释的字符数最多为255','__COMMON_LANG__@Model/Friend')),
				),
			),
		);
	}

	static function F(){
		$arrArgs = func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}
	
	public function addFriend($nUserId,$nLoginUserId){
		if(empty($nUserId)){
			$this->setErrorMessage(Dyhb::L('你没有指定添加的好友','__COMMON_LANG__@Model/Friend'));
			return false;
		}
		
		$oTryUser=UserModel::F('user_id=?',$nUserId)->getOne();
		if(empty($oTryUser['user_id'])){
			$this->setErrorMessage(Dyhb::L('你添加的好友的好友不存在','__COMMON_LANG__@Model/Friend'));
			return false;
		}
		
		$oTryFriendModel=FriendModel::F('user_id=? AND friend_friendid=? AND friend_status=1',$nLoginUserId,$nUserId)->query();
		if(!empty($oTryFriendModel['user_id'])){
			$this->setErrorMessage(Dyhb::L('此用户已经在你的好友列表中','__COMMON_LANG__@Model/Friend'));
			return false;
		}
		
		if($nUserId==$nLoginUserId){
			$this->setErrorMessage(Dyhb::L('你不能添加自己为好友','__COMMON_LANG__@Model/Friend'));
			return false;
		}
		
		$oDb=Db::RUN();
		$oTryFriendModel=FriendModel::F('user_id=? AND friend_friendid=?',$nLoginUserId,$nUserId)->query();
		if(!empty($oTryFriendModel['user_id'])){
			$sSql="UPDATE ". FriendModel::F()->query()->getTablePrefix()."friend SET friend_status=1 WHERE `friend_friendid`={$nUserId} AND `user_id`=".$nLoginUserId;
			$oDb->query($sSql);
		}else{
			$oLoginUser=UserModel::F('user_id=?',$nLoginUserId)->getOne();
			
			$oFriendModel=new FriendModel();
			$oFriendModel->user_id=$nLoginUserId;
			$oFriendModel->friend_friendid=$nUserId;
			$oFriendModel->friend_status=1;
			$oFriendModel->friend_friendusername=$oTryUser['user_name'];
			$oFriendModel->friend_username=$oLoginUser['user_name'];
			$oFriendModel->save(0);
				
			if($oFriendModel->isError()){
				$this->setErrorMessage($oFriendModel->getErrorMessage());
				return false;
			}
		}
		
		$oTryFriendModel=FriendModel::F()->where(array('user_id'=>$nUserId,'friend_friendid'=>$nLoginUserId,'friend_status'=>1))->query();
		if(!empty($oTryFriendModel['user_id'])){
			$sSql="UPDATE ".FriendModel::F()->query()->getTablePrefix()."friend SET friend_direction=3 WHERE `user_id`={$nUserId} AND `friend_friendid`=".$nLoginUserId;
			$oDb->query($sSql);
		
			$sSql="UPDATE ". FriendModel::F()->query()->getTablePrefix()."friend SET friend_direction=3 WHERE `friend_friendid`={$nUserId} AND `user_id`=".$nLoginUserId;
			$oDb->query($sSql);
		}

		// 更新我的好友和对方的粉丝数量
		$this->updateFriendAndFans($nLoginUserId,$nUserId);
		
		return true;
	}
	
	public function deleteFriend($nFriendId,$nLoginUserId,$nFan=0){/* $nFan=1 表示解除粉丝 */
		if(empty($nFriendId)){
			$this->setErrorMessage(Dyhb::L('你没有指定删除的好友','__COMMON_LANG__@Model/Friend'));
			return false;
		}

		if($nFan){
			$nTemp=$nFriendId;
			$nFriendId=$nLoginUserId;
			$nLoginUserId=$nTemp;
		}

		$oDb=Db::RUN();
		
		$sSql="UPDATE ".self::F()->query()->getTablePrefix()."friend SET friend_status=0,friend_direction=1 WHERE `friend_friendid`={$nFriendId} AND `user_id`=".$nLoginUserId;
		$oDb->query($sSql);

		$sSql="UPDATE ".self::F()->query()->getTablePrefix()."friend SET friend_direction=1 WHERE `friend_friendid`={$nLoginUserId} AND `user_id`=".$nFriendId;
		$oDb->query($sSql);

		if($nFan){// 更新我的粉丝数量和对方好友数量
			$this->updateFriendAndFans($nLoginUserId,$nFriendId);
		}else{// 更新我的好友和对方的粉丝数量
			$this->updateFriendAndFans($nLoginUserId,$nFriendId);
		}
		
		return true;
	}
	
	public function editFriendComment($nFriendId,$nLoginUserId,$sComment,$nFan=0){
		if(empty($nFriendId)){
			$this->setErrorMessage(Dyhb::L('你没有指定好友ID','__COMMON_LANG__@Model/Friend'));
			return false;
		}
		
		if(strlen($sComment)>255){
			$this->setErrorMessage(Dyhb::L('好友注释的字符数最多为255','__COMMON_LANG__@Model/Friend'));
			return false;
		}

		if($nFan){
			$sCommentField='friend_fancomment';
			$nTemp=$nFriendId;
			$nFriendId=$nLoginUserId;
			$nLoginUserId=$nTemp;
		}else{
			$sCommentField='friend_comment';
		}
		
		$oDb=Db::RUN();
		
		$sSql="UPDATE ".self::F()->query()->getTablePrefix()."friend SET {$sCommentField}='{$sComment}' WHERE `friend_status`=1 AND `friend_friendid`={$nFriendId} AND `user_id`=".$nLoginUserId;
		$oDb->query($sSql);
		
		return true;
	}
	
	static public function isAlreadyFriend($nFriendId,$nLoginUserId){
		if(empty($nFriendId) || empty($nLoginUserId)){
			return 0;
		}
		
		if($nFriendId==$nLoginUserId){
			return 2;
		}
		
		$oTryFriendModel=FriendModel::F('user_id=? AND friend_friendid=? AND friend_status=1',$nLoginUserId,$nFriendId)->query();
		if(!empty($oTryFriendModel['user_id'])){
			if($oTryFriendModel['friend_direction']==3){
				return 4;
			}else{
				return 1;
			}
		}

		$oTryFriendModel=FriendModel::F('user_id=? AND friend_friendid=? AND friend_status=1',$nFriendId,$nLoginUserId)->query();
		if(!empty($oTryFriendModel['user_id'])){
			return 3;
		}

		return 0;
	}
	
	static public function getFriendById($nUserId){
		$arrUsers=self::F('user_id=? AND friend_status=1',$nUserId)->getAll();
		
		if(is_array($arrUsers)){
			$arrUserId=array();
			foreach($arrUsers as $oUser){
				$arrUserId[]=$oUser['friend_friendid'];
			}
			
			return $arrUserId;
		}else{
			return array();
		}
	}

	public function updateFriendAndFans($nLoginUserId,$nFriendId){
		// 更新我的好友数量
		$nFriendCounts=FriendModel::F('user_id=? AND friend_status=1',$nLoginUserId)->all()->getCounts();
		$oUserCount=UsercountModel::F('user_id=?',$nLoginUserId)->getOne();
		$oUserCount->usercount_friends=$nFriendCounts;
		$oUserCount->save(0,'update');

		if($oUserCount->isError()){
			$this->setErrorMessage($oUserCount->getErrorMessage());
			return false;
		}

		// 更新对方的粉丝数量
		$nHefanCounts=FriendModel::F('friend_friendid=? AND friend_status=1',$nFriendId)->all()->getCounts();
		$oUserCount=UsercountModel::F('user_id=?',$nFriendId)->getOne();
		$oUserCount->usercount_fans=$nHefanCounts;
		$oUserCount->save(0,'update');

		if($oUserCount->isError()){
			$this->setErrorMessage($oUserCount->getErrorMessage());
			return false;
		}
	}

}
