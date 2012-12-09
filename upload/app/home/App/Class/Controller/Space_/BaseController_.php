<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   个人空间基本资料($)*/

!defined('DYHB_PATH') && exit;

class BaseController extends Controller{

	public function index(){
		require_once(Core_Extend::includeFile('function/Profile_Extend'));
		
		$nId=intval(G::getGpc('id','G'));
		if(empty($nId)){
			$nId=$GLOBALS['___login___']['user_id'];
		}
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller/Space'));
		}else{
			$this->assign('oUserInfo',$oUserInfo);
		}
		
		$sDo=G::getGpc('do','G');
		if(!in_array($sDo,array('','base','contact','edu','work','info'))){
			$sDo='';
		}
		
		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		Core_Extend::loadCache('userprofilesetting');
		$this->assign('arrUserprofilesettingDatas',$GLOBALS['_cache_']['userprofilesetting']);
		
		$arrUserprofile=$oUserInfo->userprofile->toArray();
		$this->assign('sDirthDistrict',Profile_Extend::getDistrict($arrUserprofile,'birth',false));
		$this->assign('sResideDistrict',Profile_Extend::getDistrict($arrUserprofile,'reside',false));
		
		$arrFriends=FriendModel::F('user_id=? AND friend_status=1',$nId)->order('create_dateline DESC')->limit(0,$arrOptionData['my_friend_limit_num'])->getAll();
		
		$this->assign('sDo',$sDo);
		$this->assign('nId',$nId);
		$this->assign('arrFriends',$arrFriends);

		// 用户等级名字
		$sRatingname=UserModel::getUserrating($oUserInfo->usercount->usercount_extendcredit1);
		$this->assign('sRatingname',$sRatingname);

		// 视图
		$arrProfileSetting=Profile_Extend::getProfileSetting();

		$this->_oUserInfo=$oUserInfo;

		$this->assign('arrBases',$arrProfileSetting[0]);
		$this->assign('arrContacts',$arrProfileSetting[1]);
		$this->assign('arrEdus',$arrProfileSetting[2]);
		$this->assign('arrWorks',$arrProfileSetting[3]);
		$this->assign('arrInfos',$arrProfileSetting[4]);

		$arrInfoMenus=Profile_Extend::getInfoMenu();

		$this->assign('arrInfoMenus',$arrInfoMenus);

		$this->display('space+index');
	}

	public $_oUserInfo=null;

	public function index_title_(){
		return $this->_oUserInfo['user_name'].' - '.'个人空间';
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
