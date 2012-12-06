<?php
/* [NeedForBug!] (C)Dianniu From 2010.
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
