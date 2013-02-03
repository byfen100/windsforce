<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   前台帮助信息($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HomehelpController extends InitController{

	public function index(){
		Core_Extend::doControllerAction('Homehelp@Index','index',$this);
	}

	public function show(){
		Core_Extend::doControllerAction('Homehelp@Show','index',$this);
	}

	public function homehelpcategory_($oChildcontroller){
		$oHomehelpcategory=Dyhb::instance('HomehelpcategoryModel');

		$arrHomehelpcategorys=$oHomehelpcategory->getHomehelpcategory();
		$oChildcontroller->assign('arrHomehelpcategorys',$arrHomehelpcategorys);
	}

}
