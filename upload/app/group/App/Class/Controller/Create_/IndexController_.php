<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   创建小组界面控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		$this->display('create+index');
	}

}