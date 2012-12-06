<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   注册与访问控制配置处理控制器($)*/

!defined('DYHB_PATH') && exit;

class RegisteroptionController extends OptionController{

	public function index($sModel=null,$bDisplay=true){
		$arrOptionData=$GLOBALS['_option_'];

		$this->assign('arrOptions',$arrOptionData);
		$this->display();
	}

	public function login(){
		$this->index();
	}

}
