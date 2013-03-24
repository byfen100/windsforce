<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组首页控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class PublicController extends InitController{

	public function index(){
		Core_Extend::doControllerAction('Public@Index','index');
	}

	public function newtopic(){
		Core_Extend::doControllerAction('Public@Newtopic','index');
	}
	
	public function group(){
		Core_Extend::doControllerAction('Public@Group','index');
	}

}
