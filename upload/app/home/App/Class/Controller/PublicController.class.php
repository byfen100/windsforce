<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   前台首页显示($)*/

!defined('DYHB_PATH') && exit;

class PublicController extends InitController{

	public function index(){
Dyhb::L('登录','Controller/Public');Dyhb::L('社会化绑定','Controller/Public');
		//Core_Extend::doControllerAction('Public@Index','index');
	}

	public function login(){
		Core_Extend::doControllerAction('Public@Login','index',$this);
	}

	public function socia_login(){
		Core_Extend::doControllerAction('Public@Login','socia');
	}

	public function socia_callback(){
		Core_Extend::doControllerAction('Public@Login','callback');
	}

	public function socia_bind(){
		Core_Extend::doControllerAction('Public@Login','bind');
	}

	public function socia_unbind(){
		Core_Extend::doControllerAction('Public@Login','unbind');
	}

	public function sociabind_again(){
		Core_Extend::doControllerAction('Public@Login','bind_again');
	}

	public function check_login(){
		Core_Extend::doControllerAction('Public@Login','check_login');
	}

	public function logout(){
		Core_Extend::doControllerAction('Public@Login','logout');
	}

	public function clear(){
		Core_Extend::doControllerAction('Public@Login','clear');
	}

	public function register(){
		Core_Extend::doControllerAction('Public@Register','index',$this);
	}
	
	public function check_user(){
		Core_Extend::doControllerAction('Public@Register','check_user');
	}
	
	public function check_email(){
		Core_Extend::doControllerAction('Public@Register','check_email');
	}
	
	public function register_user(){
		Core_Extend::doControllerAction('Public@Register','register_user');
	}

	public function mobile(){
		Core_Extend::doControllerAction('Public@Mobile','index');
	}

	public function sitemap(){
		Core_Extend::doControllerAction('Public@Sitemap','index');
	}

}
