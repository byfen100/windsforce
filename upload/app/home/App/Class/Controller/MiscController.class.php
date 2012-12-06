<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   主页杂项控制器($)*/

!defined('DYHB_PATH') && exit;

class MiscController extends InitController{

	public function district(){
		Core_Extend::doControllerAction('Misc@District','index');
	}

	public function newpmnum(){
		Core_Extend::doControllerAction('Misc@Newpmnum','index');
	}

	public function style(){
		Core_Extend::doControllerAction('Misc@Style','index');
	}

	public function extendstyle(){
		Core_Extend::doControllerAction('Misc@Extendstyle','index');
	}

	public function init_system(){
		Core_Extend::doControllerAction('Misc@Initsystem','index');
	}

	public function music(){
		Core_Extend::doControllerAction('Misc@Media','index');
	}	
	
	public function video(){
		Core_Extend::doControllerAction('Misc@Media','video');
	}

	public function avatar(){
		Core_Extend::doControllerAction('Misc@Avatar','index');
	}

	public function dialogstyle(){
		Core_Extend::doControllerAction('Misc@Dialogstyle','index');
	}

}
