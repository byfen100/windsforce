<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   系统积分记录($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入积分相关函数 */
require_once(Core_Extend::includeFile('function/Credit_Extend'));

class CreditrulelogController extends Controller{

	public function index(){
		// 系统积分记录
		$arrCreditrulelogs=CreditrulelogModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->order('creditrulelog_id DESC')->getAll();
		$this->assign('arrCreditrulelogs',$arrCreditrulelogs);

		// 可用积分
		$arrAvailableExtendCredits=Credit_Extend::getAvailableExtendCredits();
		$this->assign('arrAvailableExtendCredits',$arrAvailableExtendCredits);

		$this->display('spaceadmin+creditrulelog');
	}

	public function creditrulelog_title_(){
		return Dyhb::L('系统积分奖励','Controller/Spaceadmin');
	}

	public function creditrulelog_keywords_(){
		return $this->creditrulelog_title_();
	}

	public function creditrulelog_description_(){
		return $this->creditrulelog_title_();
	}

}
