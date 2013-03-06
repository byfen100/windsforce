<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   转账界面($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入积分相关函数 */
require_once(Core_Extend::includeFile('function/Credit_Extend'));

class TransferController extends Controller{

	public function index(){
		// 用户积分数据
		$nId=intval($GLOBALS['___login___']['user_id']);
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller/Spaceadmin'));
		}else{
			$this->assign('oUserInfo',$oUserInfo);
			$this->assign('oUsercount',$oUserInfo->usercount);
		}

		// 可用积分
		$arrAvailableExtendCredits=Credit_Extend::getAvailableExtendCredits();
		$this->assign('arrAvailableExtendCredits',$arrAvailableExtendCredits);

		// 提示性数据
		$nCreditstax=$GLOBALS['_option_']['credit_stax'];
		$nCreditstax=sprintf("%.2f",$nCreditstax*100);
		$this->assign('nCreditstax',$nCreditstax);
		$this->assign('nTransferMincredits',$GLOBALS['_option_']['transfermin_credits']);

		$this->display('spaceadmin+transfer');
	}

	public function transfer_title_(){
		return Dyhb::L('转账','Controller/Spaceadmin');
	}

	public function transfer_keywords_(){
		return $this->transfer_title_();
	}

	public function transfer_description_(){
		return $this->transfer_title_();
	}

}
