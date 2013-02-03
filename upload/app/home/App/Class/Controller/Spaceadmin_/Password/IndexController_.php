<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   用户密码安全($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		$arrUserData=$GLOBALS['___login___'];
		$this->assign('nUserId',$arrUserData['user_id']);
		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['seccode_changepassword_status']);

		$this->display('spaceadmin+password');
	}

	public function password_title_(){
		return Dyhb::L('修改密码','Controller/Spaceadmin');
	}

	public function password_keywords_(){
		return $this->password_title_();
	}

	public function password_description_(){
		return $this->password_title_();
	}

}
