<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   站点统计显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class StatController extends InitController{

	public function index(){
		Core_Extend::doControllerAction('Stat@Base','index');
	}
	
	public function userlist(){
		Core_Extend::doControllerAction('Stat@Userlist','index');
	}

	public function feed(){
		Core_Extend::doControllerAction('Stat@Feed','index');
	}

	public function explore(){
		Core_Extend::doControllerAction('Stat@Explore','index');
	}

	public function adminuser(){
		Core_Extend::doControllerAction('Stat@Adminuser','index');
	}

	public function newuser(){
		Core_Extend::doControllerAction('Stat@Newuser','index');
	}

	public function usertop(){
		Core_Extend::doControllerAction('Stat@Usertop','index');
	}
	
}
