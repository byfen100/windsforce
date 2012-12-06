<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   Wap首页显示($)*/

!defined('DYHB_PATH') && exit;

class PublicController extends InitController{

	public function index(){
		$this->display('public+index');
	}

}
