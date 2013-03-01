<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   积分兑换处理($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入积分相关函数 */
require_once(Core_Extend::includeFile('function/Credit_Extend'));

class DoexchangeController extends Controller{

	public function index(){
		// 用户积分数据
		$nId=intval($GLOBALS['___login___']['user_id']);
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller/Spaceadmin'));
		}
		$oUsercount=$oUserInfo->usercount;

		// 兑换检测
		$nFromcredits=intval(G::getGpc('from_credits','P'));
		$nTocredits=intval(G::getGpc('to_credits','P'));
		$nExchangeamount=intval(G::getGpc('exchange_amount','P'));
		$sUserpassword=trim(G::getGpc('user_password','P'));
		
		$arrAvailableExtendCredits=array();// 可用积分
		$arrAvailableExtendCredits=Credit_Extend::getAvailableExtendCredits();

		if(!$nFromcredits){
			$this->E(Dyhb::L('待兑换的积分不能为空','Controller/Spaceadmin'));
		}elseif(!array_key_exists($nFromcredits,$arrAvailableExtendCredits)){
			$this->E(Dyhb::L('待兑换的积分不存在','Controller/Spaceadmin'));
		}elseif($arrAvailableExtendCredits[$nFromcredits]['allowexchangeout']==0){
			$this->E(Dyhb::L('待兑换的积分不允许兑出','Controller/Spaceadmin'));
		}

		if(!$nTocredits){
			 $this->E(Dyhb::L('兑换成的积分不能为空','Controller/Spaceadmin'));
		}elseif(!array_key_exists($nTocredits,$arrAvailableExtendCredits)){
			$this->E(Dyhb::L('待兑换的积分不存在','Controller/Spaceadmin'));
		}elseif($arrAvailableExtendCredits[$nTocredits]['allowexchangein']==0){
			$this->E(Dyhb::L('兑换成的积分不允许兑入','Controller/Spaceadmin'));
		}

		if($nFromcredits==$nTocredits){
			$this->E(Dyhb::L('同种积分之间无法兑换','Controller/Spaceadmin'));
		}
//G::dump($arrAvailableExtendCredits);
		
		if(!$arrAvailableExtendCredits[$nTocredits]['ratio']){
			$this->E(Dyhb::L('兑换成的积分的兑换比率必须大于0','Controller/Spaceadmin'));
		}
		
		if($arrAvailableExtendCredits[$nTocredits]['ratio']<$arrAvailableExtendCredits[$nFromcredits]['ratio']){
			$nNetamount=ceil($nExchangeamount*$arrAvailableExtendCredits[$nTocredits]['ratio']/$arrAvailableExtendCredits[$nFromcredits]['ratio']*(1+$GLOBALS['_option_']['credit_stax']));
		}else{
			$nNetamount=floor($nExchangeamount*$arrAvailableExtendCredits[$nTocredits]['ratio']/$arrAvailableExtendCredits[$nFromcredits]['ratio']*(1+$GLOBALS['_option_']['credit_stax']));
		}

		if(!$nNetamount){
			$this->E(Dyhb::L('兑换成的积分的数量必须大于0','Controller/Spaceadmin'));
		}elseif($nExchangeamount<=0){
			$this->E(Dyhb::L('待兑换的积分的数量必须大于0','Controller/Spaceadmin'));
		}
		
		



		// 提示性数据
		//$nCreditstax=$GLOBALS['_option_']['credit_stax'];
		//$nCreditstax=sprintf("%.2f",$nCreditstax*100);
		//$this->assign('nCreditstax',$nCreditstax);
		//$this->assign('nExchangeMincredits',$GLOBALS['_option_']['exchange_mincredits']);
	}

}
