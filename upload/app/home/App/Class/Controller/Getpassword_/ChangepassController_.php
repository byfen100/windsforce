<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   完成密码修改($)*/

!defined('DYHB_PATH') && exit;

class ChangepassController extends GlobalchildController{

	public function index(){
		$this->_oParentcontroller->check_seccode(true);

		$sPassword=trim(G::getGpc('user_password','P'));
		$sNewPassword=trim(G::getGpc('new_password','P'));
		$sEmail=trim(G::getGpc('user_email','P'));
		$nAppeal=intval(G::getGpc('appeal','P'));
		$nUserId=intval(G::getGpc('user_id','P'));
		
		if(!empty($nUserId)&&$nAppeal==1){
			$oUser=UserModel::F('user_id=?',$nUserId)->getOne();
		}else{
			$oUser=UserModel::F('user_email=?',$sEmail)->getOne();
		}

		if(empty($oUser->user_id)){
			$this->E(Dyhb::L('Email账号不存在','Controller/Getpassword'));
		}
		if($oUser->user_status==0){
			$this->E(Dyhb::L('该账户已经被禁止','Controller/Getpassword'));
		}

		$oUserModel=Dyhb::instance('UserModel');
		$oUserModel->changePassword($sPassword,$sNewPassword,'',true,$oUser->toArray(),true);
		if($oUserModel->isError()){
			$this->E($oUserModel->getErrorMessage());
		}else{
			$oUser->user_temppassword='';
			$oUser->save(0,'update');
			if($oUser->isError()){
				$this->E($oUser->getErrorMessage());
			}

			$this->S(Dyhb::L('密码修改成功，你需要重新登录','Controller/Getpassword'));
		}
	}

}
