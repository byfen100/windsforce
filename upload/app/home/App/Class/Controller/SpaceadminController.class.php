<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   前台个人中心管理($)*/

!defined('DYHB_PATH') && exit;

class SpaceadminController extends InitController{

	public function init__(){
		parent::init__();

		$this->is_login();
	}

	public function index(){
		Core_Extend::doControllerAction('Spaceadmin@Information/Index','index');
	}

	public function change_info(){
		Core_Extend::doControllerAction('Spaceadmin@Information/Change','index',$this);
	}

	public function avatar(){
		Core_Extend::doControllerAction('Spaceadmin@Avatar/Index','index');
	}

	public function avatar_upload(){
		Core_Extend::doControllerAction('Spaceadmin@Avatar/Upload','index');
	}

	public function avatar_savecrop(){
		Core_Extend::doControllerAction('Spaceadmin@Avatar/Savecrop','index');
	}

	public function avatar_un(){
		Core_Extend::doControllerAction('Spaceadmin@Avatar/Un','index');
	}

	public function password(){
		Core_Extend::doControllerAction('Spaceadmin@Password/Index','index');
	}

	public function change_pass(){
		Core_Extend::doControllerAction('Spaceadmin@Password/Change','index',$this);
	}

	public function socia(){
		Core_Extend::doControllerAction('Spaceadmin@Socia/Index','index');
	}

	public function socia_account(){
		Core_Extend::doControllerAction('Spaceadmin@Socia','account');
	}

	public function tag(){
		Core_Extend::doControllerAction('Spaceadmin@Hometag/Index','index');
	}

	public function add_hometag(){
		Core_Extend::doControllerAction('Spaceadmin@Hometag/Add','index');
	}

	public function delete_hometag(){
		Core_Extend::doControllerAction('Spaceadmin@Hometag/Delete','index');
	}

	public function promotion(){
		Core_Extend::doControllerAction('Spaceadmin@Promotion/Index','index');
	}

	public function verifyemail(){
		Core_Extend::doControllerAction('Spaceadmin@Verifyemail/Index','index');
	}

	public function dorevifyemail(){
		Core_Extend::doControllerAction('Spaceadmin@Verifyemail/Do','index');
	}

	public function checkrevifyemail(){
		Core_Extend::doControllerAction('Spaceadmin@Verifyemail/Check','index');
	}

	public function unrevifyemail(){
		Core_Extend::doControllerAction('Spaceadmin@Verifyemail/Un','index');
	}

}
