<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   前台首页显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class PublicController extends InitController{

	public function index(){
		Core_Extend::doControllerAction('Public@Index','index');
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
		Core_Extend::doControllerAction('Public@Login','check_login',$this);
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
		Core_Extend::doControllerAction('Public@Register','register_user',$this);
	}

	public function mobile(){
		Core_Extend::doControllerAction('Public@Mobile','index');
	}

	public function sitemap(){
		Core_Extend::doControllerAction('Public@Sitemap','index');
	}

	public function rbacerror(){
		Core_Extend::doControllerAction('Public@Rbacerror','index');
	}

	public function myrbac(){
		Core_Extend::doControllerAction('Public@Myrbac','index');
	}

	public function role(){
		Core_Extend::doControllerAction('Public@Role','index');
	}

}
