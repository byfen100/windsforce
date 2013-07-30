<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
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
