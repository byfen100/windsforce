<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动用户中心显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UcenterController extends InitController{
	
	public function init__(){
		parent::init__();

		$this->is_login();
	}
	
	public function index(){
		Core_Extend::doControllerAction('Ucenter@Event/Index','index');
	}

	public function join(){
		Core_Extend::doControllerAction('Ucenter@Event/Join','index');
	}
	
	public function join_delete(){
		Core_Extend::doControllerAction('Ucenter@Event/Joindelete','index');
	}

	public function attention(){
		Core_Extend::doControllerAction('Ucenter@Event/Attention','index');
	}
	
	public function attention_delete(){
		Core_Extend::doControllerAction('Ucenter@Event/Attentiondelete','index');
	}
	
}
