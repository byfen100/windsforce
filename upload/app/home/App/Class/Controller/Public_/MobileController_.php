<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   手机版($)*/

!defined('DYHB_PATH') && exit;

class MobileController extends Controller{

	public function index(){
		$this->display('public+mobile');
	}

}
