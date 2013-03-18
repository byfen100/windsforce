<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   个人空间好友&&粉丝($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入个人信息处理函数 */
require_once(Core_Extend::includeFile('function/Profile_Extend'));

class FriendController extends Controller{

	public $_oUserInfo=null;
	public $_bFan=false;

	public function index(){
		$nId=intval(G::getGpc('id','G'));
		$nFan=intval(G::getGpc('fan','G'));
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller/Space'));
		}else{
			$this->assign('oUserInfo',$oUserInfo);
			$this->_oUserInfo=$oUserInfo;
		}

		if($nFan==1){
			$this->_bFan=true;
		}
		
		// 读取好友数据
		$arrWhere=array();
		
		if($nFan==1){
			$arrWhere['friend_friendid']=$nId;
		}else{
			$arrWhere['user_id']=$nId;
		}
		
		$arrOptionData=$GLOBALS['_cache_']['home_option'];
	
		// 好友
		$arrUsers=null;

		$arrWhere['friend_status']=1;
		$nTotalRecord=FriendModel::F()->where($arrWhere)->all()->getCounts();
		
		$oPage=Page::RUN($nTotalRecord,$arrOptionData['friend_list_num'],G::getGpc('page','G'));
		
		$arrFriends=FriendModel::F()->where($arrWhere)->order('create_dateline DESC')->limit($oPage->returnPageStart(),$arrOptionData['friend_list_num'])->getAll();

		if(is_array($arrFriends)){
			$arrUserWhere=array();

			$arrTempdata=array();
			foreach($arrFriends as $oFriend){
				$arrTempdata[]=$nFan==1?$oFriend['user_id']:$oFriend['friend_friendid'];
			}

			$arrUserWhere['user_id']=array('in',$arrTempdata);
			$arrUserWhere['user_status']=1;

			$arrUsers=UserModel::F()->where($arrUserWhere)->order('create_dateline DESC')->getAll();
		}


		$this->assign('arrUsers',$arrUsers);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('nId',$nId);

		$this->display('space+friend');
	}

	public function index_title_(){
		return $this->_oUserInfo['user_name'].' - '.($this->_bFan===true?Dyhb::L('我的粉丝','Controller/Space'):Dyhb::L('我的好友','Controller/Space'));
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
