<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   短消息控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

require_once(Core_Extend::includeFile('function/Pm_Extend'));

class PmController extends InitController{

	public function init__(){
		parent::init__();

		$this->is_login();
	}

	public function dialog_add(){
		Core_Extend::doControllerAction('Pm@Dialogadd','index',$this);
	}

	public function pmnew(){
		Core_Extend::doControllerAction('Pm@Pmnew','index',$this);
	}

	public function sendpm(){
		Core_Extend::doControllerAction('Pm@Sendpm','index',$this);
	}
	
	public function index(){
		Core_Extend::doControllerAction('Pm@Index','index');
	}

	public function del_one_pm($nId='',$nUserId='',$nFromId=''){
		Core_Extend::doControllerAction('Pm@Delonepm','index');
	}

	public function delselect(){
		Core_Extend::doControllerAction('Pm@Delonepm','select');
	}

	public function del_my_one_pm($nId='',$nUserId=''){
		Core_Extend::doControllerAction('Pm@Delmyonepm','index');
	}

	public function delmyselect(){
		Core_Extend::doControllerAction('Pm@Delmyonepm','myselect');
	}

	public function show(){
		Core_Extend::doControllerAction('Pm@Show','index',$this);
	}

	public function truncatepm(){
		Core_Extend::doControllerAction('Pm@Truncatepm','index');
	}
	
	public function readselect(){
		Core_Extend::doControllerAction('Pm@Readselect','index');
	}

	public function delete_systempm(){
		Core_Extend::doControllerAction('Pm@Deletesystempm','index');
	}

	public function check_pm(){
		Core_Extend::doControllerAction('Pm@Checkpm','index');
	}

}
