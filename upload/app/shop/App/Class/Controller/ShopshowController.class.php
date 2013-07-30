<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商城在线展览控制器($)*/

!defined('DYHB_PATH') && exit;

class ShopshowController extends InitController{

	public function index(){
		$this->display('shopshow+index');
	}

	public function lists(){
		$this->display('shopshow+lists');
	}

	public function show(){
		$this->display('shopshow+show');
	}

}
