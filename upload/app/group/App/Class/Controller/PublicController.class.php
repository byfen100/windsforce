<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组首页控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class PublicController extends InitController{

	public function index(){
		if($GLOBALS['_cache_']['group_option']['newtopic_default']==1){
			Core_Extend::doControllerAction('Public@Newtopic','index');
		}else{
			Core_Extend::doControllerAction('Public@Index','index');
		}
	}

	public function set_homepagestyle(){
		Core_Extend::doControllerAction('Public@Sethomepagestyle','index');
	}

	public function newtopic(){
		if($GLOBALS['_cache_']['group_option']['newtopic_default']==1){
			Core_Extend::doControllerAction('Public@Index','index');
		}else{
			
			Core_Extend::doControllerAction('Public@Newtopic','index');
		}
	}
	
	public function group(){
		Core_Extend::doControllerAction('Public@Group','index');
	}

}
