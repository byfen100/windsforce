<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   用户密码安全($)*/

!defined('DYHB_PATH') && exit;

class PasswordController extends Controller{

	public function index(){
		$arrUserData=$GLOBALS['___login___'];
		$this->assign('nUserId',$arrUserData['user_id']);
		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['seccode_changepassword_status']);

		$this->display('spaceadmin+password');
	}

	public function password_title_(){
		return '修改密码';
	}

	public function password_keywords_(){
		return $this->password_title_();
	}

	public function password_description_(){
		return $this->password_title_();
	}

	public function change(){
		if($GLOBALS['_option_']['seccode_changepassword_status']==1){
			$this->check_seccode(true);
		}

		$sPassword=G::getGpc('user_password','P');
		$sNewPassword=G::getGpc('new_password','P');
		$sOldPassword=G::getGpc('old_password','P');

		$oUserModel=Dyhb::instance('UserModel');
		$oUserModel->changePassword($sPassword,$sNewPassword,$sOldPassword);
		if($oUserModel->isError()){
			$this->E($oUserModel->getErrorMessage());
		}else{
			$this->S(Dyhb::L('密码修改成功，你需要重新登录','Controller/Spaceadmin'));
		}
	}

}
