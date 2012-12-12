<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   前台站点信息($)*/

!defined('DYHB_PATH') && exit;

class HomesiteController extends InitController{

	public function site(){
		Core_Extend::doControllerAction('Homesite@Site','index',$this);
	}
	
	public function aboutus(){
		Core_Extend::doControllerAction('Homesite@Aboutus','index',$this);
	}

	public function contactus(){
		Core_Extend::doControllerAction('Homesite@Contactus','index',$this);
	}

	public function agreement(){
		Core_Extend::doControllerAction('Homesite@Agreement','index',$this);
	}

	public function privacy(){
		Core_Extend::doControllerAction('Homesite@Privacy','index',$this);
	}

	public function site_($oChildcontroller,$sName){
		$oHomesite=HomesiteModel::F("homesite_name='{$sName}'")->getOne();
		$oChildcontroller->assign('sContent',Core_Extend::replaceSiteVar($oHomesite['homesite_content']));
		$oChildcontroller->assign('sTitle',$oHomesite['homesite_nikename']);
		
		$arrHomesites=HomesiteModel::F()->getAll();
		$oChildcontroller->assign('arrHomesites',$arrHomesites);

		$oChildcontroller->_oHomesite=$oHomesite;

		$oChildcontroller->display('homesite+index');
	}

}
