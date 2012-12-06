<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   短消息配置控制器($)*/

!defined('DYHB_PATH') && exit;

class PmoptionController extends OptionController{

	public function index($sModel=null,$bDisplay=true){
		$arrOptionData=$GLOBALS['_option_'];

		$this->assign('arrOptions',$arrOptionData);
		$this->display();
	}

	public function sound(){
		$this->index();
	}

}
