<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   删除验证信息($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UnController extends Controller{

	public function index(){
		if($GLOBALS['___login___']['user_isverify']==0){
			$this->E(Dyhb::L('Email验证信息不存在，无需删除','Controller/Spaceadmin'));
		}

		$sEmail=trim($GLOBALS['___login___']['user_email']);
		if(empty($sEmail)){
			$this->E(Dyhb::L('Email地址不能为空','Controller/Spaceadmin'));
		}

		Check::RUN();
		if(!Check::C($sEmail,'email')){
			$this->E(Dyhb::L('Email格式不正确','Controller/Spaceadmin'));
		}

		$oUser=UserModel::F('user_email=?',$sEmail)->getOne();
		if(empty($oUser->user_id)){
			$this->E(Dyhb::L('Email账号不存在','Controller/Spaceadmin'));
		}
		if($oUser->user_status==0){
			$this->E(Dyhb::L('该账户已经被禁止','Controller/Spaceadmin'));
		}

		// 删除验证状态
		$oUser->user_verifycode='';
		$oUser->user_isverify='0';
		$oUser->save(0,'update');

		if($oUser->isError()){
			$this->E($oUser->getErrorMessage());
		}

		$this->assign('__JumpUrl__',Dyhb::U('home://spaceadmin/verifyemail'));
		$this->S(Dyhb::L('成功删除Email验证信息','Controller/Spaceadmin'));
	}

}
