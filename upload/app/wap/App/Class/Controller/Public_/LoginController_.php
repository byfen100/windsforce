<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户登录显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

// 导入社会化登录组件
Dyhb::import(WINDSFORCE_PATH.'/source/extension/socialization');

class LoginController extends GlobalchildController{

	public function index(){
		$sReferer=trim(G::getGpc('referer'));
		$nRbac=intval(G::getGpc('rbac','G'));
		$nLoginview=intval(G::getGpc('loginview','G'));
		
		if($GLOBALS['___login___']!==false){
			$this->_oParentcontroller->wap_mes(Dyhb::L('你已经登录','Controller'),Dyhb::U('wap://public/index'),0);
		}

		$this->assign('sReferer',$sReferer);
		$this->assign('nRbac',$nRbac);
		$this->assign('nLoginview',$nLoginview);

		$this->display('public+login');
	}

	public function check(){
		$sUserName=G::getGpc('user_name','P');
		$sPassword=G::getGpc('user_password','P');

		if(empty($sUserName)){
			$this->_oParentcontroller->wap_mes(Dyhb::L('帐号或者E-mail不能为空','Controller'),Dyhb::U('wap://public/login'),0);
		}elseif(empty($sPassword)){
			$this->_oParentcontroller->wap_mes(Dyhb::L('密码不能为空','Controller'),Dyhb::U('wap://public/login'),0);
		}

		Check::RUN();
		if(Check::C($sUserName,'email')){
			$bEmail=true;
			unset($_POST['user_name']);
		}else{
			$bEmail=false;
		}

		$oUserModel=Dyhb::instance('UserModel');
		$oUserModel->checkLoginCommon($sUserName,$sPassword,$bEmail,'wap');

		if($oUserModel->isError()){
			$this->_oParentcontroller->wap_mes($oUserModel->getErrorMessage(),Dyhb::U('wap://public/login'),0);
		}else{
			if(G::getGpc('windsforce_referer')){
				$sUrl=G::getGpc('windsforce_referer');
			}else{
				$sUrl=Dyhb::U('wap://ucenter/index');
			}

			$oLoginUser=UserModel::F('user_name=?',$sUserName)->getOne();

			Core_Extend::updateCreditByAction('daylogin',$oLoginUser['user_id']);

			$this->_oParentcontroller->wap_mes(Dyhb::L('Hello %s,你成功登录','Controller',null,$sUserName),$sUrl);
		}
	}

	public function out(){
		$nReferer=intval(G::getGpc('referer','G'));
		
		if(UserModel::M()->isLogin()){
			$arrUserData=$GLOBALS['___login___'];
			if(!isset($arrUserData['session_auth_key'])){
				$arrUserData['session_auth_key']='';
			}
			UserModel::M()->replaceSession($arrUserData['session_hash'],$arrUserData['user_id'],$arrUserData['session_auth_key']);
			UserModel::M()->logout();

			$GLOBALS['___login___']=false;

			Core_Extend::clearCookie();
			Socia::clearCookie(true);

			if($nReferer==1 && !empty($_SERVER['HTTP_REFERER'])){
				$sJumpUrl=$_SERVER['HTTP_REFERER'];
			}else{
				$sJumpUrl=Dyhb::U('wap://public/login');
			}
	
			$this->_oParentcontroller->wap_mes(Dyhb::L('登出成功','Controller'),$sJumpUrl);
		}else{
			$this->_oParentcontroller->wap_mes(Dyhb::L('已经登出','Controller'),'',0);
		}
	}

	public function login_title_(){
		return Dyhb::L('用户登录','Controller');
	}

	public function login_keywords_(){
		return $this->login_title_();
	}

	public function login_description_(){
		return $this->login_title_();
	}

}
