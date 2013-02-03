<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   密码修改确认($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ChangeController extends GlobalchildController{

	public function index(){
		if($GLOBALS['_option_']['seccode_changepassword_status']==1){
			$this->_oParentcontroller->check_seccode(true);
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
