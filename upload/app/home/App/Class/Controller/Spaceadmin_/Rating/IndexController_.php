<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   积分管理信息($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入积分相关函数 */
require_once(Core_Extend::includeFile('function/Credit_Extend'));

class IndexController extends Controller{

	public function index(){
		$nId=intval($GLOBALS['___login___']['user_id']);
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller'));
		}else{
			$this->assign('oUserInfo',$oUserInfo);
		}

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

		// 最近积分记录
		$arrCreditlogs=CreditlogModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->order('create_dateline DESC')->limit(0,10)->getAll();
		$this->assign('arrCreditlogs',$arrCreditlogs);
		
		$this->display('spaceadmin+rating');
	}

	public function rating_title_(){
		return Dyhb::L('积分','Controller');
	}

	public function rating_keywords_(){
		return $this->rating_title_();
	}

	public function rating_description_(){
		return $this->rating_title_();
	}

}
