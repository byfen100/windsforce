<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户中心显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UcenterController extends InitController{

	public function init__(){
		parent::init__();

		if(ACTION_NAME!=='view'){
			$this->is_login();
		}
	}
	
	public function index(){
		Core_Extend::doControllerAction('Ucenter@Homefresh/Index','index');
	}

	public function add_homefresh(){
		Core_Extend::doControllerAction('Ucenter@Homefresh/Add','index');
	}

	public function view(){
		Core_Extend::doControllerAction('Ucenter@Homefresh/View','index');
	}

	public function homefreshtopic(){
		Core_Extend::doControllerAction('Ucenter@Homefresh/Topic','index');
	}

	public function add_homefreshcomment(){
		Core_Extend::doControllerAction('Ucenter@Homefresh/Addcomment','index');
	}

	public function update_homefreshgoodnum(){
		Core_Extend::doControllerAction('Ucenter@Homefresh/Updategoodnum','index');
	}	
	
	public function audit_homefreshcomment(){
		Core_Extend::doControllerAction('Ucenter@Homefresh/Audit','index');
	}

	public function feed(){
		Core_Extend::doControllerAction('Ucenter@Feed/Index','index');
	}
	
}
