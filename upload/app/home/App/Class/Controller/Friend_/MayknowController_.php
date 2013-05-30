<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   你可能认识的好友($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 
 * 我和某个人有共同的好友的话，那么我们可能认识 
 * 通过比对我的好友和我的好友的好友可以找出来
 */
class MayknowController extends Controller{

	public function index(){
		require_once(Core_Extend::includeFile('function/Profile_Extend'));
		
		$nMaxnum=36;
		$nLoginuserid=intval($GLOBALS['___login___']['user_id']);
		
		// 初始化我的好友ID和好友的好友ID
		$arrMyfirendUserids=$arrFriendUserids=$arrFriendlistUserids=array();
		
		// 我的好友数据
		$nI=0;
		$arrMyfriends=FriendModel::F('user_id=? AND friend_status=1',$nLoginuserid)->getAll();
		if(is_array($arrMyfriends)){
			foreach($arrMyfriends as $oMyfriends){
				// 读取100位之内好友数据
				if($nI<100){
					$arrFriendUserids[$oMyfriends['friend_friendid']]=$oMyfriends['friend_friendid'];
				}
				$arrMyfirendUserids[$oMyfriends['friend_friendid']]=$oMyfriends['friend_friendid'];

				$nI++;
			}
		}

		$arrMyfirendUserids[$nLoginuserid]=$nLoginuserid;

		// 查找我的好友的好友数据
		$nI=0;
		$arrFriendlists=array();

		if($arrFriendUserids){
			$arrFriendfriends=FriendModel::F('friend_friendid in('.implode(',',$arrFriendUserids).') AND friend_status=?',1)->limit(0,200)->getAll();
			$arrFriendUserids[$nLoginuserid]=$nLoginuserid;

			if(is_array($arrFriendfriends)){
				foreach($arrFriendfriends as $oFriendfriend){
					$arrValue=$oFriendfriend->toArray();

					if(empty($arrMyfirendUserids[$arrValue['user_id']])){
						$arrFriendlistUserids[]=$arrValue['user_id'];
						$nI++;
						if($nI>=$nMaxnum){
							break;
						}
					}
				}
			}

			if($arrFriendlistUserids){
				$arrFriendlists=UserModel::F('user_id in('.implode(',',$arrFriendlistUserids).') AND user_status=?',1)->order('user_id DESC')->getAll();
				if(!is_array($arrFriendlists)){
					$arrFriendlists=array();
				}
			}
		}

		$this->assign('arrFriendlists',$arrFriendlists);
		$this->display('friend+mayknow');
	}

	public function mayknow_title_(){
		return Dyhb::L('可能认识的人','Controller');
	}

	public function mayknow_keywords_(){
		return $this->mayknow_title_();
	}

	public function mayknow_description_(){
		return $this->mayknow_title_();
	}

}
