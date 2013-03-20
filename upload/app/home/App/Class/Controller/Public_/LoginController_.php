<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   前台登陆($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

// 导入社会化登录组件
Dyhb::import(WINDSFORCE_PATH.'/source/extension/socialization');

class LoginController extends GlobalchildController{

	public function index(){
		$nInajax=intval(G::getGpc('inajax','G'));
		
		if($GLOBALS['___login___']!==false){
			$this->assign('__JumpUrl__',__APP__);
			$this->E(Dyhb::L('你已经登录','Controller/Public'));
		}

		Core_Extend::loadCache('sociatype');

		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['seccode_login_status']);
		$this->assign('nRememberTime',$GLOBALS['_option_']['remember_time']);
		$this->assign('arrBindeds',$GLOBALS['_cache_']['sociatype']);

		$sRbacerrorreferer=trim(Dyhb::cookie('_rbacerror_referer_'));

		if(empty($sRbacerrorreferer)){
			Dyhb::cookie('_rbacerror_referer_',null,-1);
		}

		$this->assign('sRbacerrorreferer',$sRbacerrorreferer);

		if($nInajax==1){
			$this->display('public+ajaxlogin');
		}else{
			$this->display('public+login');
		}
	}

	public function login_title_(){
		return Dyhb::L('登录','Controller/Public');
	}

	public function login_keywords_(){
		return $this->login_title_();
	}

	public function login_description_(){
		return $this->login_title_();
	}

	public function socia(){
		$sVendor=trim(G::getGpc('vendor','G'));

		$oSocia=Dyhb::instance('Socia',$sVendor);
		$oSocia->login();
		
		if($oSocia->isError()){
			$this->E($oSocia->getErrorMessage());
		}
	}

	public function callback(){
		$sVendor=trim(G::getGpc('vendor','G'));
		
		$oSocia=Dyhb::instance('Socia',$sVendor);
		$arrUser=$oSocia->callback();
		$oSocia->bind();
		
		if($oSocia->isError()){
			$this->E($oSocia->getErrorMessage());
		}
	}

	public function bind(){
		$arrUser=Socia::getUser();

		if(empty($arrUser)){
			$this->assign('__JumpUrl__',Dyhb::U('home://public/login'));
			$this->E(Dyhb::L('你尚未登录社会化帐号','Controller/Public'));
		}

		$this->assign('arrUser',$arrUser);
		$this->assign('sRandPassword',G::randString(10));

		$this->display('public+sociabind');
	}

	public function socia_bind_title_(){
		return Dyhb::L('社会化绑定','Controller/Public');
	}

	public function socia_bind_keywords_(){
		return $this->socia_bind_title_();
	}

	public function socia_bind_description_(){
		return $this->socia_bind_title_();
	}

	public function unbind(){
		$sVendor=trim(G::getGpc('vendor','G'));

		$oSociauserMeta=SociauserModel::M();
		$oSociauserMeta->deleteWhere(array('sociauser_vendor'=>$sVendor,'user_id'=>$GLOBALS['___login___']['user_id']));

		if($oSociauserMeta->isError()){
			$this->E($oSociauserMeta->getErrorMessage());
		}

		$this->assign('__JumpUrl__',Dyhb::U('home://ucenter/index'));
		$this->S(Dyhb::L('帐号解除绑定成功','Controller/Public'));
	}

	public function bind_again(){
		$arrUser=Socia::getUser();

		if(empty($arrUser)){
			$this->E(Dyhb::L('你尚未登录社会化帐号','Controller/Public'));
		}

		$oSocia=Dyhb::instance('Socia',$arrUser['sociauser_vendor']);
		$oSocia->bind();
	}

	public function check_login(){
		if($GLOBALS['_option_']['seccode_login_status']==1){
			$this->_oParentcontroller->check_seccode(true);
		}

		$sUserName=G::getGpc('user_name','P');
		$sPassword=G::getGpc('user_password','P');

		if(empty($sUserName)){
			$this->E(Dyhb::L('帐号或者E-mail不能为空','Controller/Public'));
		}elseif(empty($sPassword)){
			$this->E(Dyhb::L('密码不能为空','Controller/Public'));
		}

		Check::RUN();
		if(Check::C($sUserName,'email')){
			$bEmail=true;
			unset($_POST['user_name']);
		}else{
			$bEmail=false;
		}

		$oUserModel=Dyhb::instance('UserModel');
		$oUserModel->checkLoginCommon($sUserName,$sPassword,$bEmail,'admin');

		if($oUserModel->isError()){
			$this->E($oUserModel->getErrorMessage());
		}else{
			if(Dyhb::cookie('windsforce_referer')){
				$sUrl=Dyhb::cookie('windsforce_referer');
				Dyhb::cookie('windsforce_referer',null,-1);
			}elseif(G::getGpc('windsforce_referer')){
				$sUrl=G::getGpc('windsforce_referer');
			}else{
				$sUrl=Dyhb::U('home://ucenter/index');
			}

			$oLoginUser=UserModel::F('user_name=?',$sUserName)->getOne();
			Core_Extend::updateCreditByAction('daylogin',$oLoginUser['user_id']);

			// 如果第三方网站已登录，则进行绑定
			if(Socia::getUser()){
				// 绑定社会化登录数据，以便于下次直接调用
				$oSociauser=Dyhb::instance('SociauserModel');
				$oSociauser->processBind($oLoginUser['user_id']);

				if($oSociauser->isError()){
					$this->E($oSociauser->getErrorMessage());
				}
			}

			$this->A(array('url'=>$sUrl),Dyhb::L('Hello %s,你成功登录','Controller/Public',null,$sUserName),1);
		}
	}

	public function logout(){
		$nRefer=intval(G::getGpc('refer','G'));
		
		if(UserModel::M()->isLogin()){
			$arrUserData=$GLOBALS['___login___'];
			if(!isset($arrUserData['session_auth_key'])){
				$arrUserData['session_auth_key']='';
			}
			UserModel::M()->replaceSession($arrUserData['session_hash'],$arrUserData['user_id'],$arrUserData['session_auth_key']);
			UserModel::M()->logout();

			$GLOBALS['___login___']=false;

			Dyhb::cookie('SOCIA_LOGIN',NULL,-1);
			Dyhb::cookie('SOCIA_LOGIN_TYPE',NULL,-1);
			Dyhb::cookie("_socia_access_token_",NULL,-1);
			Dyhb::cookie('_socia_openid_',NULL,-1);
			Dyhb::cookie('_socia_state_',NULL,-1);

			if($nRefer==1 && !empty($_SERVER['HTTP_REFERER'])){
				$sJumpUrl=$_SERVER['HTTP_REFERER'];
			}else{
				$sJumpUrl=Dyhb::U('home://public/login');
			}
	
			$this->assign("__JumpUrl__",$sJumpUrl);
			$this->S(Dyhb::L('登出成功','Controller/Public'));
		}else{
			$this->E(Dyhb::L('已经登出','Controller/Public'));
		}
	}

	public function clear(){
		UserModel::M()->clearThisCookie();
		$this->S(Dyhb::L('清理登录痕迹成功','Controller/Public'));
	}

}
