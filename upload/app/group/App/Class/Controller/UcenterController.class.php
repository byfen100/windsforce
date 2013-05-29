<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组用户中心显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UcenterController extends InitController{
	
	public function init__(){
		parent::init__();

		$this->is_login();
	}
	
	public function index(){
		Core_Extend::doControllerAction('Ucenter@Grouptopic/Index','index');
	}

	public function lovetopic(){
		Core_Extend::doControllerAction('Ucenter@Grouptopic/Love','index');
	}
	
	public function lovetopic_delete(){
		Core_Extend::doControllerAction('Ucenter@Grouptopic/Lovetopicdelete','index');
	}
	
}
