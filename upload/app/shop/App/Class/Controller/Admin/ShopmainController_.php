<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商城入口控制器($)*/

!defined('DYHB_PATH') && exit;

/** 导入商城模型 */
Dyhb::import(NEEDFORBUG_PATH.'/app/shop/App/Class/Model');

class ShopmainController extends InitController{
	
	public function index($sModel=null,$bDisplay=true){
		$this->display(Admin_Extend::template('shop','shopmain/index'));
	}

}