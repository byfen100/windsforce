<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   创建群组控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CreateController extends InitController{

	public function index(){
		Core_Extend::doControllerAction('Create@Index','index');
	}

	public function add(){
		Core_Extend::doControllerAction('Create@Add','index');
	}

}
