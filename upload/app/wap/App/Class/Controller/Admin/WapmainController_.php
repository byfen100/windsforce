<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   Wap入口控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入主页模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/wap/App/Class/Model');

class WapmainController extends InitController{

	public function index($sModel=null,$bDisplay=true){
		$this->display(Admin_Extend::template('wap','wapmain/index'));
	}

}
