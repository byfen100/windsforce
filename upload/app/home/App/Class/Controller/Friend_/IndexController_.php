<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   好友首页($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入个人信息函数 */
require_once(Core_Extend::includeFile('function/Profile_Extend'));

class IndexController extends Controller{

	public function index(){
		$arrWhere=array();
		
		$sType=trim(G::getGpc('type','G'));
		if($sType=='fan'){
			$arrWhere['friend_friendid']=$GLOBALS['___login___']['user_id'];
		}else{
			$arrWhere['user_id']=$GLOBALS['___login___']['user_id'];;
		}
		
		$sKey=trim(G::getGpc('key'));
		if(!empty($sKey)){
			if($sType=='fan'){
				$arrWhere['friend_username']=array('like',"%".$sKey."%");
			}else{
				$arrWhere['friend_friendusername']=array('like',"%".$sKey."%");
			}
		}
		
		$arrOptionData=$GLOBALS['_cache_']['home_option'];
	
		// 好友
		$arrWhere['friend_status']=1;
		$nTotalRecord=FriendModel::F()->where($arrWhere)->all()->getCounts();
		
		$oPage=Page::RUN($nTotalRecord,$arrOptionData['friend_list_num'],G::getGpc('page','G'));
		
		$arrFriends=FriendModel::F()->where($arrWhere)->order('create_dateline DESC')->limit($oPage->returnPageStart(),$arrOptionData['friend_list_num'])->getAll();
		$this->assign('arrFriends',$arrFriends);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('sType',$sType);
		$this->assign('sKey',$sKey);
		
		$this->display('friend+index');
	}

	public function get_gender_icon($nUserid){
		$oUserprofile=UserprofileModel::F('user_id=?',$nUserid)->getOne();

		if(!empty($oUserprofile['user_id'])){
			$nGender=$oUserprofile['userprofile_gender'];
		}else{
			$nGender=0;
		}

		return Profile_Extend::getUserprofilegender($nGender);
	}

	public function index_title_(){
		if(G::getGpc('type','G')=='fan'){
			return Dyhb::L('我的粉丝','Controller');
		}else{
			return Dyhb::L('我的好友','Controller');
		}
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
