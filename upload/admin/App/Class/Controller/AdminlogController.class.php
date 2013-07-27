<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   后台操作记录控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入杂项函数 */
require(Core_Extend::includeFile('function/Misc_Extend'));

class AdminlogController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller'));
		}
	}
	
	public function filter_(&$arrMap){
		$arrMap['adminlog_username']=array('like',"%".G::getGpc('loginlog_username')."%");
	}

	public function clear(){
		$this->display();
	}

	public function clear_all(){
		$oDb=Db::RUN();
		$sSql="TRUNCATE ".PmModel::F()->query()->getTablePrefix()."adminlog";
		$oDb->query($sSql);
		
		$this->assign('__JumpUrl__',Dyhb::U('adminlog/index'));
		
		$this->S(Dyhb::L('清空后台管理数据成功','Controller'));
	}

}
