<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   Wap首页显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class PublicController extends InitController{

	public function index(){
		$this->display('public+index');
	}

	public function login(){
		$this->display('public+login');
	}

}
