<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   短消息配置控制器($Liu.XiangMin)*/

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
