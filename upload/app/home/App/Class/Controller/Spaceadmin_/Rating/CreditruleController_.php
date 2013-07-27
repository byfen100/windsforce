<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   积分规则($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入积分相关函数 */
require_once(Core_Extend::includeFile('function/Credit_Extend'));

class CreditruleController extends Controller{

	public function index(){
		// 积分规则
		$arrCreditrules=CreditruleModel::F()->all()->order('creditrule_id ASC')->query();
		$this->assign('arrCreditrules',$arrCreditrules);

		// 可用积分类型
		$arrAvailableExtendCredits=Credit_Extend::getAvailableExtendCredits();
		$this->assign('arrAvailableExtendCredits',$arrAvailableExtendCredits);

		$this->display('spaceadmin+creditrule');
	}

	public function creditrule_title_(){
		return Dyhb::L('积分规则','Controller');
	}

	public function creditrule_keywords_(){
		return $this->creditrule_title_();
	}

	public function creditrule_description_(){
		return $this->creditrule_title_();
	}

}
