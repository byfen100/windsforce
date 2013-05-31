<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Wap个人中心($Liu.XiangMin)*/

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
		Core_Extend::doControllerAction('Ucenter@Homefresh/Add','index',$this);
	}

	public function view(){
		Core_Extend::doControllerAction('Ucenter@Homefresh/View','index',$this);
	}

}
