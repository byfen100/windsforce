<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   站点搜索功能($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SearchController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['_option_']['allow_search']==0){
			$this->E(Dyhb::L('系统关闭了搜索功能','Controller/Search'));
		}
	}
	
	public function index(){
		Core_Extend::doControllerAction('Search@Index','index');
	}
	
	public function user(){
		Core_Extend::doControllerAction('Search@User','index');
	}

	public function result(){
		Core_Extend::doControllerAction('Search@Index','result');
	}
	
}
