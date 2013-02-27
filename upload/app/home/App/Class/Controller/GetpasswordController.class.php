<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   前台找回密码控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GetpasswordController extends InitController{

	public function index(){
		Core_Extend::doControllerAction('Getpassword@Index','index',$this);
	}

	public function email(){
		Core_Extend::doControllerAction('Getpassword@Email','index',$this);
	}

	public function reset(){
		Core_Extend::doControllerAction('Getpassword@Reset','index');
	}

	public function change_pass(){
		Core_Extend::doControllerAction('Getpassword@Changepass','index',$this);
	}

}
