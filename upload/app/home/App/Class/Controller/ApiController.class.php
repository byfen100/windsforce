<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   主页接口控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;
!defined('IN_API') && exit();

class ApiController extends InitController{

	public function newuser(){
		Core_Extend::doControllerAction('Api@Newuser','index');
	}

}
