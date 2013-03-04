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
		}elseif($oUsercount['usercount_extendcredit'.$nFromcredits]-$nNetamount<($nExchangemincredits=$GLOBALS['_option_']['exchange_mincredits'])){
			$this->E(Dyhb::L('兑换最低余额不能小于 %d','Controller/Spaceadmin',null,$nExchangemincredits));
		}
		
		// 验证登录密码
		$oUserModel=Dyhb::instance('UserModel');
		$oUserModel->checkLogin($GLOBALS['___login___']['user_name'],$sUserpassword,false,'home');
		if($oUserModel->isError()){
			$this->E(Dyhb::L('登录密码输入失败','Controller/Spaceadmin'));
		}

		// 确认兑换
		try{
			Credit_Extend::updateUsercount($GLOBALS['___login___']['user_id'],array('extcredits'.$nFromcredits=>-$nNetamount,'extcredits'.$nTocredits=>$nExchangeamount),'exchange',$GLOBALS['___login___']['user_id']);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		$this->assign('__JumpUrl__',Dyhb::U('home://spaceadmin/exchange'));
		$this->S(Dyhb::L('兑换成功','Controller/Spaceadmin'));
	}

}
