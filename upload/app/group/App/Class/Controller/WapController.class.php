<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组Wap控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** wap页面 */
define('IN_WAP',true);

class WapController extends GlobalwapController{

	public function index(){
		Core_Extend::doControllerAction('Wap@Grouptopic/Index','index',$this);
	}
	
	public function show(){
		Core_Extend::doControllerAction('Wap@Grouptopic/Show','index',$this);
	}

}
