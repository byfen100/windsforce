<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Service首页显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class PublicController extends InitController{

	public function index(){
		$this->display('public+index');
	}

	public function notice(){
		$this->display('public+notice');
	}

	public function license(){
		$this->display('public+license');
	}
	
	public function step(){
		$this->display('public+step');
	}
	
	public function pay(){
		$this->display('public+pay');
	}

}
