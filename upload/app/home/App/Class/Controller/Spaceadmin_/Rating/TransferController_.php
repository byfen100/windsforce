<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   转账界面($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入积分相关函数 */
require_once(Core_Extend::includeFile('function/Credit_Extend'));

class TransferController extends Controller{

	public function index(){
		// 可用积分
		$arrAvailableExtendCredits=array();
		$arrAvailableExtendCredits=Credit_Extend::getAvailableExtendCredits();
		$this->assign('arrAvailableExtendCredits',$arrAvailableExtendCredits);

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
