<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Service入口控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ServicemainController extends InitController{

	public function index($sModel=null,$bDisplay=true){
		$this->display(Admin_Extend::template('service','servicemain/index'));
	}

}
