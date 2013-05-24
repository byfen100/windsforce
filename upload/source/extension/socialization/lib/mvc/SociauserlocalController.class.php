<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   社会化登录数据本地化($Liu.XiangMin)*/

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
				Socia::clearCookie();
				$this->U('home://ucenter/index');
			}else{
				$this->localLogin($nUserbinded);
			}
		}else{//本地用户未绑定
			if($nUserlocal){
				// 本地用户已登录，进行绑定处理
				$oSociauser->processBind($nUserlocal);
				Socia::clearCookie();
				$this->U('home://ucenter/index');
			}else{
				// 前往绑定页面，注册新用户或者使用已有帐号登录完成后再次转向绑定页面
				$this->U('home://public/socia_bind');
			}
		}
	}

	public function localLogin($nUserid){
		$oUser=UserModel::F('user_id=? AND user_status=1',$nUserid)->getOne();

		if(!empty($oUser['user_id'])){
			$oUserModel=Dyhb::instance('UserModel');
			UserModel::M()->changeSettings('encode_type','cleartext');
			$oUserModel->checkLoginCommon($oUser['user_name'],$oUser['user_password'],false,'home',$GLOBALS['socia_login_time']);
			UserModel::M()->changeSettings('encode_type','authcode');
		
			if($oUserModel->isError()){
				$this->E($oUserModel->getErrorMessage());
			}

			if(G::getGpc('windsforce_referer')){
				$sUrl=G::getGpc('windsforce_referer');
			}else{
				$sUrl=Dyhb::U('home://ucenter/index');
			}

			Core_Extend::updateCreditByAction('daylogin',$oUser['user_id']);

			Socia::clearCookie();

			$this->assign('__JumpUrl__',$sUrl);
			$this->S(Dyhb::L('Hello %s,你成功登录','Controller/Public',null,$oUser['user_name']));
		}else{
			return false;
		}
	}

}
