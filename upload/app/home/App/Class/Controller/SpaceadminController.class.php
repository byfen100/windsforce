<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   前台个人中心管理($)*/

!defined('DYHB_PATH') && exit;

class SpaceadminController extends InitController{

	public function init__(){
		parent::init__();

		$this->is_login();
	}

	public function index(){
		Core_Extend::doControllerAction('Spaceadmin@Information','index');
	}

	public function change_info(){
		Core_Extend::doControllerAction('Spaceadmin@Information','change');
	}

	public function avatar(){
		Core_Extend::doControllerAction('Spaceadmin@Avatar','index');
	}

	public function avatar_upload(){
		Core_Extend::doControllerAction('Spaceadmin@Avatar','upload');
	}

	public function avatar_savecrop(){
		Core_Extend::doControllerAction('Spaceadmin@Avatar','save_crop');
	}

	public function password(){
		Core_Extend::doControllerAction('Spaceadmin@Password','index');
	}

	public function change_pass(){
		Core_Extend::doControllerAction('Spaceadmin@Password','change');
	}

	public function socia(){
		Core_Extend::doControllerAction('Spaceadmin@Socia','index');
	}

	public function socia_account(){
		Core_Extend::doControllerAction('Spaceadmin@Socia','account');
	}

	public function tag(){
		Core_Extend::doControllerAction('Spaceadmin@Hometag','index');
	}

	public function add_hometag(){
		Core_Extend::doControllerAction('Spaceadmin@Hometag','add');
	}

	public function delete_hometag(){
		Core_Extend::doControllerAction('Spaceadmin@Hometag','delete');
	}

	public function promotion(){
		Core_Extend::doControllerAction('Spaceadmin@Promotion','index');
	}

}
