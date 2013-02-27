<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GrouptopicController extends InitController{

	public function init__(){
		parent::init__();

		if(ACTION_NAME!=='view'){
			$this->is_login();
		}
	}
	
	public function add(){
		Core_Extend::doControllerAction('Grouptopic@Add','index');
	}	
	
	public function add_topic(){
		Core_Extend::doControllerAction('Grouptopic@Addtopic','index');
	}

	public function view(){
		Core_Extend::doControllerAction('Grouptopic@View','index');
	}

	public function edit(){
		Core_Extend::doControllerAction('Grouptopic@Edit','index');
	}

	public function submit_edit(){
		Core_Extend::doControllerAction('Grouptopic@Submitedit','index');
	}

	public function reply(){
		Core_Extend::doControllerAction('Grouptopic@Reply','index');
	}
	
	public function add_reply(){
		Core_Extend::doControllerAction('Grouptopic@Addreply','index');
	}

}
