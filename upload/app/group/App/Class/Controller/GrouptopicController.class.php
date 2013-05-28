<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GrouptopicController extends InitController{

	public function init__(){
		parent::init__();

		if(!in_array(ACTION_NAME,array('view','set_grouptopicstyle','set_grouptopicside'))){
			$this->is_login();
		}
	}
	
	public function add(){
		Core_Extend::doControllerAction('Grouptopic@Add','index');
	}
	
	public function add_topic(){
		Core_Extend::doControllerAction('Grouptopic@Addtopic','index',$this);
	}

	public function view(){
		Core_Extend::doControllerAction('Grouptopic@View','index');
	}

	public function edit(){
		Core_Extend::doControllerAction('Grouptopic@Edit','index');
	}

	public function submit_edit(){
		Core_Extend::doControllerAction('Grouptopic@Submitedit','index',$this);
	}

	public function reply(){
		Core_Extend::doControllerAction('Grouptopic@Reply','index');
	}
	
	public function add_reply(){
		Core_Extend::doControllerAction('Grouptopic@Addreply','index',$this);
	}

	public function set_grouptopicstyle(){
		Core_Extend::doControllerAction('Grouptopic@Setgrouptopicstyle','index');
	}

	public function set_grouptopicside(){
		Core_Extend::doControllerAction('Grouptopic@Setgrouptopicside','index');
	}	
	
	public function commenttopic_dialog(){
		Core_Extend::doControllerAction('Grouptopic@Commenttopicdialog','index');
	}
	
	public function editcommenttopic_dialog(){
		Core_Extend::doControllerAction('Grouptopic@Editcommenttopicdialog','index');
	}

	public function submit_reply(){
		Core_Extend::doControllerAction('Grouptopic@Submitreply','index',$this);
	}

	public function printtable(){
		Core_Extend::doControllerAction('Grouptopic@Printtable','index',$this);
	}
	
	public function next(){
		Core_Extend::doControllerAction('Grouptopic@Next','index',$this);
	}
	
	public function prev(){
		Core_Extend::doControllerAction('Grouptopic@Prev','index',$this);
	}
	
	public function readtopic(){
		Core_Extend::doControllerAction('Grouptopic@Readtopic','index',$this);
	}

	public function love(){
		Core_Extend::doControllerAction('Grouptopic@Love','index',$this);
	}

	public function love_add(){
		Core_Extend::doControllerAction('Grouptopic@Loveadd','index',$this);
	}

}
