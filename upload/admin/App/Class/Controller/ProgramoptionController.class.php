<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   程序版权配置处理控制器($)*/

!defined('DYHB_PATH') && exit;

class ProgramoptionController extends OptionController{

	public function index($sModel=null,$bDisplay=true){
		$arrOptionData=$GLOBALS['_option_'];

		$this->assign('arrOptions',$arrOptionData);
		$this->display();
	}

}
