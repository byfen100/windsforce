<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   搜索处理控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SearchoptionController extends OptionController{

	public function index($sModel=null,$bDisplay=true){
		$arrOptionData=$GLOBALS['_option_'];

		$this->assign('arrOptions',$arrOptionData);
		$this->display();
	}

}
