<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组标签控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TagController extends InitController{

	public function index(){
		Core_Extend::doControllerAction('Tag@Index','index');
	}

	public function show(){
		Core_Extend::doControllerAction('Tag@Show','index');
	}

	public function top(){
		Core_Extend::doControllerAction('Tag@Top','index');
	}

}
