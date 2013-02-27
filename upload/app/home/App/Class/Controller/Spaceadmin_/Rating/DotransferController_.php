<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   执行转账($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入积分相关函数 */
require_once(Core_Extend::includeFile('function/Credit_Extend'));

class DotransferController extends Controller{

	public function index(){
		$nId=intval($GLOBALS['___login___']['user_id']);
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller/Spaceadmin'));
		}
		$oUsercount=$oUserInfo->usercount;

		// 转账检测
		$nTransferamount=intval(G::getGpc('transfer_amount','P'));
		$sTousername=trim(G::getGpc('to_username','P'));
		$sUserpassword=trim(G::getGpc('user_password','P'));

		if($nTransferamount<=0){
			$this->E(Dyhb::L('你要转账的积分输入有误','Controller/Spaceadmin'));
		}elseif($oUsercount['usercount_extendcredit2']-$nTransferamount<($nTransferminCredits=$GLOBALS['_option_']['transfermin_credits'])){
			$this->E(Dyhb::L('转账最低余额不能小于 %d','Controller/Spaceadmin',null,$nTransferminCredits));
		}elseif(!($nNetamount=floor($nTransferamount*(1-$GLOBALS['_option_']['credit_stax'])))){
			$this->E(Dyhb::L('扣除积分交易税后余额为0','Controller/Spaceadmin'));
		}

		if(!$sTousername){
			$this->E(Dyhb::L('接收转账的用户不能为空','Controller/Spaceadmin'));
		}

		if($sTousername==$GLOBALS['___login___']['user_name']){
			$this->E(Dyhb::L('你不能给自己转账','Controller/Spaceadmin'));
		}
		$oTouser=UserModel::F()->getByuser_name($sTousername);
		if(empty($oTouser['user_id'])){
			$this->E(Dyhb::L('接收转账的用户不存在','Controller/Spaceadmin'));
		}

		// 验证登录密码
		$oUserModel=Dyhb::instance('UserModel');
		$oUserModel->checkLogin($GLOBALS['___login___']['user_name'],$sUserpassword,false,'home');
		if($oUserModel->isError()){
			$this->E(Dyhb::L('登录密码输入失败','Controller/Spaceadmin'));
		}

		// 确认转账
		try{
			Credit_Extend::updateUsercount($GLOBALS['___login___']['user_id'],array('extcredits2'=>-$nTransferamount),'transferout',$oTouser['user_id']);
			Credit_Extend::updateUsercount($oTouser['user_id'],array('extcredits2'=>$nNetamount),'transferin',$GLOBALS['___login___']['user_id']);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		$this->assign('__JumpUrl__',Dyhb::U('home://spaceadmin/transfer'));
		$this->S(Dyhb::L('转账成功','Controller/Spaceadmin'));
	}

}
