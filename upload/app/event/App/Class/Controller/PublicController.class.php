<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动App首页显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class PublicController extends InitController{

	public function index(){
		Core_Extend::doControllerAction('Public@Index','index');
	}

	public function event(){
		Core_Extend::doControllerAction('Public@Event','index');
	}

}
