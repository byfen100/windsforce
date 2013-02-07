<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   系统积分记录($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CreditrulelogController extends Controller{

	public function index(){
		$arrCreditrulelogs=CreditrulelogModel::F('user_id')->order('creditrulelog_id DESC')->getAll();
		G::dump($arrCreditrulelogs);
	/*$count = C::t('common_credit_rule_log')->count_by_uid($_G['uid']);
	if($count) {
		$rulelogs = C::t('common_credit_rule_log')->fetch_all_by_uid($_G['uid'], $start, $perpage);
		$rules = C::t('common_credit_rule')->fetch_all_by_rid(C::t('common_credit_rule_log')->get_rids());
		foreach($rulelogs as $value) {
			$value['rulename'] = $rules[$value['rid']]['rulename'];
			$list[] = $value;
		}
	}*/
		$this->assign('arrCreditrulelogs',$arrCreditrulelogs);

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
