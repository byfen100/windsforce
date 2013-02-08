<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   访问推广($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入积分相关函数 */
require_once(Core_Extend::includeFile('function/Credit_Extend'));

class IndexController extends Controller{

	public function index(){
		$arrData=array();

		// 启用的积分类型
		$arrAvailableExtendCredits=Credit_Extend::getAvailableExtendCredits();
		
		// 赠送积分规则
		$arrCreditrules=CreditruleModel::F()->where(array('creditrule_action'=>array('in','promotion_register,promotion_visit')))->order('creditrule_id DESC')->getAll();
		if(is_array($arrCreditrules)){
			foreach($arrCreditrules as $oCreditrule){
				if(is_array($arrAvailableExtendCredits)){
					foreach($arrAvailableExtendCredits as $nKey=>$arrAvailableExtendCredit){
						$arrData[$oCreditrule['creditrule_action']][]=array('title'=>$arrAvailableExtendCredit['title'],'data'=>$oCreditrule['creditrule_extendcredit'.$nKey]);
					}
				}
			}
		}

		$this->assign('arrCreditdata',$arrData);
		
		// URL衔接信息
		$this->assign('nUserId',Core_Extend::aidencode(intval($GLOBALS['___login___']['user_id'])));
		$this->assign('sUserName',rawurlencode(trim($GLOBALS['___login___']['user_name'])));
		$this->assign('sSiteName',$GLOBALS['_option_']['site_name']);
		$this->assign('sSiteUrl',$GLOBALS['_option_']['site_url']);

		$this->display('spaceadmin+promotion');
	}

	public function promotion_title_(){
		return Dyhb::L('访问推广','Controller/Spaceadmin');
	}

	public function promotion_keywords_(){
		return $this->promotion_title_();
	}

	public function promotion_description_(){
		return $this->promotion_title_();
	}

}
