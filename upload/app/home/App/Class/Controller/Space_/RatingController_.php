<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   个人空间等级显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入积分相关函数 */
require_once(Core_Extend::includeFile('function/Credit_Extend'));

class RatingController extends Controller{
	
	public $_oUserInfo=null;
	
	public function index(){
		Core_Extend::loadCache('rating');
		Core_Extend::loadCache('ratinggroup');

		$nId=intval(G::getGpc('id','G'));
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller'));
		}else{
			$this->assign('oUserInfo',$oUserInfo);
		}

		$this->_oUserInfo=$oUserInfo;

		// 用户等级名字
		$nUserscore=$oUserInfo->usercount->usercount_extendcredit1;
		$arrRatinginfo=UserModel::getUserrating($nUserscore,false);
		$this->assign('arrRatinginfo',$arrRatinginfo);
		$this->assign('nUserscore',$nUserscore);
		$this->assign('oUsercount',$oUserInfo->usercount);

		// 所有可用积分
		$arrAvailableExtendCredits=Credit_Extend::getAvailableExtendCredits();
		$this->assign('arrAvailableExtendCredits',$arrAvailableExtendCredits);

		$this->assign('nId',$nId);
		$this->assign('oUserInfo',$oUserInfo);

		$this->display('space+rating');
	}

	public function index_title_(){
		return $this->_oUserInfo['user_name'].' - '.Dyhb::L('积分','Controller');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
