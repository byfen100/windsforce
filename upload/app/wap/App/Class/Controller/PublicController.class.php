<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Wap首页显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class PublicController extends InitController{

	public function index(){
		Core_Extend::doControllerAction('Public@Index','index');
	}

	public function login(){
		Core_Extend::doControllerAction('Public@Login','index',$this);
	}
	
	public function check_login(){
		Core_Extend::doControllerAction('Public@Login','check',$this);
	}
	
	public function logout(){
		Core_Extend::doControllerAction('Public@Login','out',$this);
	}

}
