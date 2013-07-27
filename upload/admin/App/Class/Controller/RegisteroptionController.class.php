<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   注册与访问控制配置处理控制器($Liu.XiangMin)*/

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

	public function visite(){
		$this->index();
	}

}
