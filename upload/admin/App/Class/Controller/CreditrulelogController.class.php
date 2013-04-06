<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户系统奖励控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入积分相关函数 */
require_once(Core_Extend::includeFile('function/Credit_Extend'));

class CreditrulelogController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller/Common'));
		}
	}

	public function filter_(&$arrMap){
		$nUserid=intval(G::getGpc('uid','G'));

		$oUser=UserModel::F('user_id=?',$nUserid)->getOne();
		if(!empty($oUser['user_id'])){
			$arrMap['user_id']=$nUserid;

			$this->assign('oUser',$oUser);
		}
	}

	public function bIndex_(){
		// 可用积分
		$arrAvailableExtendCredits=Credit_Extend::getAvailableExtendCredits();
		$this->assign('arrAvailableExtendCredits',$arrAvailableExtendCredits);
	}

	public function clear(){
		$nUserid=intval(G::getGpc('id'));

		if(empty($nUserid)){
			$this->E(Dyhb::L('你没有指定待清空系统奖励数据的用户','Controller/Creditrulelog'));
		}

		$oUser=UserModel::F('user_id=?',$nUserid)->getOne();

		if(empty($oUser['user_id'])){
			$this->E(Dyhb::L('待清空系统奖励数据的用户不存在','Controller/Creditrulelog'));
		}
		
		// 执行删除
		$oCreditrulelogMeta=CreditrulelogModel::M();
		$oCreditrulelogMeta->deleteWhere(array('user_id'=>$nUserid));
			
		if($oCreditrulelogMeta->isError()){
			$this->E($oCreditrulelogMeta->getErrorMessage());
		}

		$this->S(Dyhb::L('清空系统奖励数据成功','Controller/Creditrulelog'));
	}

}
