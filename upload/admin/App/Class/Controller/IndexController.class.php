<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   后台首页显示($)*/

!defined('DYHB_PATH') && exit;

class IndexController extends InitController{

	public function index($sModel=null,$bDisplay=true){
		if($GLOBALS['___login___']===false){
			UserModel::M()->clearThisCookie();// 清理COOKIE
			$this->assign('__JumpUrl__',Dyhb::U('public/login'));
			$this->E(Dyhb::L('你没有登录','Controller/Common'));
		}

		$this->display();
	}

}
