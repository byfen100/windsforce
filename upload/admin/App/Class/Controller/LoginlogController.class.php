<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   登录记录控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class LoginlogController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller/Common'));
		}
	}
	
	public function filter_(&$arrMap){
		$arrMap['loginlog_username']=array('like',"%".G::getGpc('loginlog_username')."%");
	}

	public function clear(){
		$this->display();
	}

	public function clear_all(){
		$oDb=Db::RUN();
		$sSql="TRUNCATE ".PmModel::F()->query()->getTablePrefix()."loginlog";
		$oDb->query($sSql);
		
		$this->assign('__JumpUrl__',Dyhb::U('loginlog/index'));
		
		$this->S(Dyhb::L('清空登录数据成功','Controller/Loginlog'));
	}

}
