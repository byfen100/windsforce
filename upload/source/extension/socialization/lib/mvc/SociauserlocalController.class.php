<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   社会化登录数据本地化($)*/

!defined('DYHB_PATH') && exit;

class SociauserlocalController extends InitController{

	public function checkLogin(){
		return $GLOBALS['___login___']===FALSE?FALSE:$GLOBALS['___login___']['user_id'];
	}
	
	public function bind(){
		// 创建模型
		$oSociauser=Dyhb::instance('SociauserModel');

		$nUserlocal=$oSociauser->checkLogin();
		$nUserbinded=$oSociauser->checkBinded();

		// 本地用户已绑定
		if($nUserbinded){
			if($nUserlocal){
				$this->U('home://ucenter/index');
			}else{
				$this->localLogin($nUserbinded);
			}
		}else{//本地用户未绑定
			if($nUserlocal){
				// 本地用户已登录，进行绑定处理
				$oSociauser->processBind($nUserlocal);
				$this->U('home://ucenter/index');
			}else{
				// 前往绑定页面，注册新用户或者使用已有帐号登录完成后再次转向绑定页面
				$this->U('home://public/socia_bind');
			}
		}
	}

	public function localLogin($nUserid){
		$oUser=UserModel::F('user_id=?',$nUserid)->getOne();

		if(!empty($oUser['user_id'])){
			$oUserModel=Dyhb::instance('UserModel');
			UserModel::M()->changeSettings('encode_type','cleartext');
			$oUserModel->checkLogin($oUser['user_name'],$oUser['user_password'],false,'home');
			UserModel::M()->changeSettings('encode_type','authcode');
		
			if($oUserModel->isError()){
				$this->E($oUserModel->getErrorMessage());
			}

			$this->assign('__JumpUrl__',Dyhb::U('home://ucenter/index'));
			$this->S(Dyhb::L('Hello %s,你成功登录','Controller/Public',null,$oUser['user_name']));
		}else{
			return false;
		}
	}

}
