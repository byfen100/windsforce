<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   群组首页控制器($)*/

!defined('DYHB_PATH') && exit;

class PublicController extends InitController{

	public function index(){
		Core_Extend::doControllerAction('Public@Index','index');
	}

	public function newtopic(){
		Core_Extend::doControllerAction('Public@Newtopic','index');
	}

}
