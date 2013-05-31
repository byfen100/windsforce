<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   积分兑换界面($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入积分相关函数 */
require_once(Core_Extend::includeFile('function/Credit_Extend'));

class ExchangeController extends Controller{

	public function index(){
		// 用户积分数据
		$nId=intval($GLOBALS['___login___']['user_id']);
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller'));
		}else{
			$this->assign('oUserInfo',$oUserInfo);
			$this->assign('oUsercount',$oUserInfo->usercount);
		}

		// 可用积分
		$arrAvailableExtendCredits=Credit_Extend::getAvailableExtendCredits();
		$this->assign('arrAvailableExtendCredits',$arrAvailableExtendCredits);

		// 提示性数据
		$this->assign('nExchangeMincredits',$GLOBALS['_option_']['exchange_mincredits']);

		$this->display('spaceadmin+exchange');
	}

	public function exchange_title_(){
		return Dyhb::L('积分兑换','Controller');
	}

	public function exchange_keywords_(){
		return $this->exchange_title_();
	}

	public function exchange_description_(){
		return $this->exchange_title_();
	}

}
