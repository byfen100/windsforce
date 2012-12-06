<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城艺术家控制器($)*/

!defined('DYHB_PATH') && exit;

class ShopartistController extends InitController{

	public function index(){
		$this->display('shopartist+index');
	}

	public function lists(){
		$this->display('shopartist+lists');
	}

	public function show(){
		$this->display('shopartist+view');
	}

}
