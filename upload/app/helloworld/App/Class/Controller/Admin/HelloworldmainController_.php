<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   Helloworld入口控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入主页模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/helloworld/App/Class/Model');

class HelloworldmainController extends InitController{

	public function index($sModel=null,$bDisplay=true){
		$this->display(Admin_Extend::template('helloworld','helloworldmain/index'));
	}

}
