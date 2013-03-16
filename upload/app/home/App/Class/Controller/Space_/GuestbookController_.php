<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   个人空间留言板($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入个人信息处理函数 */
require_once(Core_Extend::includeFile('function/Profile_Extend'));

class GuestbookController extends Controller{

	public $_oUserInfo=null;

	public function index(){
		$nId=intval(G::getGpc('id','G'));
		if(empty($nId)){
			$nId=$GLOBALS['___login___']['user_id'];
		}
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller/Space'));
		}else{
			$this->assign('oUserInfo',$oUserInfo);
			$this->_oUserInfo=$oUserInfo;
		}

		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		// 取得个人主页
		$oUserprofile=UserprofileModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getOne();

		// 用户等级名字
		$nUserscore=$oUserInfo->usercount->usercount_extendcredit1;
		$arrRatinginfo=UserModel::getUserrating($nUserscore,false);
		$this->assign('arrRatinginfo',$arrRatinginfo);
		$this->assign('nUserscore',$nUserscore);

		// 获取留言列表
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
		$oPage=Page::RUN($nTotalRecord,$arrOptionData['homefreshcomment_list_num'],G::getGpc('page','G'));
		$arrUserguestbookLists=UserguestbookModel::F()->where($arrWhere)->all()->order('`create_dateline` DESC')->limit($oPage->returnPageStart(),$arrOptionData['homefreshcomment_list_num'])->getAll();

		$this->assign('sUsersite',$oUserprofile['userprofile_site']);
		$this->assign('nId',$nId);
		$this->assign('nDisplaySeccode',$GLOBALS['_cache_']['home_option']['seccode_comment_status']);
		$this->assign('nTotalUserguestbook',$nTotalRecord);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('arrUserguestbookLists',$arrUserguestbookLists);

		$this->display('space+guestbook');
	}

	public function index_title_(){
		return $this->_oUserInfo['user_name'].' - '.Dyhb::L('我的留言板','Controller/Space');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
