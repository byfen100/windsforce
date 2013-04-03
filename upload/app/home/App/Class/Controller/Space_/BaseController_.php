<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   个人空间基本资料($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入个人信息处理函数 */
require_once(Core_Extend::includeFile('function/Profile_Extend'));

class BaseController extends Controller{
	
	public $_oUserInfo=null;
	
	public function index(){
		$nId=intval(G::getGpc('id','G'));
		
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
		$nUserscore=$oUserInfo->usercount->usercount_extendcredit1;
		$arrRatinginfo=UserModel::getUserrating($nUserscore,false);
		$this->assign('arrRatinginfo',$arrRatinginfo);
		$this->assign('nUserscore',$nUserscore);

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

		// 最近留言
		$arrWhere=array();
		$arrWhere['userguestbook_parentid']=0;
		$arrWhere['userguestbook_status']=1;
		$arrWhere['userguestbook_userid']=$nId;

		if($GLOBALS['___login___']['user_id']!=$oUserInfo['user_id']){
			$arrWhere['userguestbook_auditpass']=1;
			$this->assign('bAuditpass',false);
		}else{
			$this->assign('bAuditpass',true);
		}

		$nTotalRecord=UserguestbookModel::F()->where($arrWhere)->all()->getCounts();
		$arrUserguestbookLists=UserguestbookModel::F()->where($arrWhere)->limit(0,$arrOptionData['homefreshcomment_list_num'])->all()->order('`create_dateline` DESC')->limit(0,$arrOptionData['homefreshcomment_list_num'])->getAll();

		$this->assign('nDisplaySeccode',$GLOBALS['_cache_']['home_option']['seccode_comment_status']);
		$this->assign('sUsersite',$arrUserprofile['userprofile_site']);
		$this->assign('nTotalUserguestbook',$nTotalRecord);
		$this->assign('arrUserguestbookLists',$arrUserguestbookLists);

		$this->display('space+index');
	}

	public function index_title_(){
		return $this->_oUserInfo['user_name'].' - '.Dyhb::L('个人空间','Controller/Space');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
