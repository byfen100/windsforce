<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组搜索功能($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SearchController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['_option_']['allow_search']==0){
			$this->E(Dyhb::L('系统关闭了搜索功能','Controller'));
		}
	}

	public function index(){
		Core_Extend::doControllerAction('Search@Index','index');
	}

	public function parse(){
		Core_Extend::doControllerAction('Search@Index','parse');
	}
	
	public function result(){
		Core_Extend::doControllerAction('Search@Index','result');
	}
	
	public function group(){
		Core_Extend::doControllerAction('Search@Group','index');
	}
	
	public function groupresult(){
		Core_Extend::doControllerAction('Search@Group','result');
	}
	
}
