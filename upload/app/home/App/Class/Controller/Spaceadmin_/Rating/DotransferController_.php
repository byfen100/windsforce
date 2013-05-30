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
			$this->E(Dyhb::L('你指定的用户不存在','Controller'));
		}
		$oUsercount=$oUserInfo->usercount;

		// 转账检测
		$nTransferamount=intval(G::getGpc('transfer_amount','P'));
		$sTousername=trim(G::getGpc('to_username','P'));
		$sUserpassword=trim(G::getGpc('password','P'));
		$sTransfermessage=trim(G::getGpc('transfer_message','P'));

		$arrAvailableExtendCredits=Credit_Extend::getAvailableExtendCredits();// 可用积分

		if($nTransferamount<=0){
			$this->E(Dyhb::L('你要转账的积分输入有误','Controller'));
		}elseif($oUsercount['usercount_extendcredit2']-$nTransferamount<($nTransferminCredits=$GLOBALS['_option_']['transfermin_credits'])){
			$this->E(Dyhb::L('转账最低余额不能小于 %d','Controller',null,$nTransferminCredits));
		}elseif(!($nNetamount=floor($nTransferamount*(1-$GLOBALS['_option_']['credit_stax'])))){
			$this->E(Dyhb::L('扣除积分交易税后余额为0','Controller'));
		}

		if(!$sTousername){
			$this->E(Dyhb::L('接收转账的用户不能为空','Controller'));
		}

		if($sTousername==$GLOBALS['___login___']['user_name']){
			$this->E(Dyhb::L('你不能给自己转账','Controller'));
		}
		$oTouser=UserModel::F()->getByuser_name($sTousername);
		if(empty($oTouser['user_id'])){
			$this->E(Dyhb::L('接收转账的用户不存在','Controller'));
		}

		// 验证登录密码
		$oUserlogin=UserModel::M()->checkLogin($GLOBALS['___login___']['user_name'],$sUserpassword,false,false,false);
		if(UserModel::M()->isBehaviorError()){
			$this->E(Dyhb::L('登录密码输入失败','Controller').'<br/>'.UserModel::M()->getBehaviorErrorMessage());
		}

		// 确认转账
		try{
			Credit_Extend::updateUsercount($GLOBALS['___login___']['user_id'],array('extcredits2'=>-$nTransferamount),'transferout',$oTouser['user_id']);
			Credit_Extend::updateUsercount($oTouser['user_id'],array('extcredits2'=>$nNetamount),'transferin',$GLOBALS['___login___']['user_id']);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		// 发送提醒
		$sCreditchange=$arrAvailableExtendCredits['2']['title'].'&nbsp;+'.$nNetamount.'&nbsp;&nbsp;';
		
		$sNoticetemplate='<div class="notice_credit"><span class="notice_title">'.Dyhb::L('您收到一笔来自','Controller').'&nbsp;<a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('的积分转账','Controller').'&nbsp;'.$sCreditchange.'</span>';
		
		if($sTransfermessage){
			$sNoticetemplate.='<div class="notice_content"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('说','Controller').':&nbsp;<span class="notice_quote"><span class="notice_quoteinfo">'.$sTransfermessage.'</span></span></div>';
		};
		
		$sNoticetemplate.='<div class="notice_action"><a href="{@creditlog_link}">'.Dyhb::L('查看','Controller').'</a></div></div>';

		$arrNoticedata=array(
			'@space_link'=>'home://space@?id='.$GLOBALS['___login___']['user_id'],
			'user_name'=>$GLOBALS['___login___']['user_name'],
			'@creditlog_link'=>'home://spaceadmin/creditlog',
		);

		try{
			Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$oTouser['user_id'],'credit');
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		$this->assign('__JumpUrl__',Dyhb::U('home://spaceadmin/transfer'));
		$this->S(Dyhb::L('转账成功','Controller'));
	}

}
