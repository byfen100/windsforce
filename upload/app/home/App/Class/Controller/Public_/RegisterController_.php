<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   前台用户注册($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

// 导入社会化登录组件
Dyhb::import(WINDSFORCE_PATH.'/source/extension/socialization');

class RegisterController extends GlobalchildController{

	public function index(){
		$nInajax=intval(G::getGpc('inajax','G'));
		$sRefer=trim(G::getGpc('refer','G'));
		
		if($GLOBALS['___login___']!==false){
			$this->assign('__JumpUrl__',__APP__);
			$this->E(Dyhb::L('你已经登录','Controller/Public'));
		}

		if($GLOBALS['_option_']['disallowed_register']){
			$this->E(Dyhb::L('系统关闭了用户注册','Controller/Public'));
		}

		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['seccode_register_status']);
		$this->assign('sRefer',$sRefer);

		if($nInajax==1){
			$this->display('public+ajaxregister');
		}else{
			$this->display('public+register');
		}
	}

	public function register_title_(){
		return Dyhb::L('注册','Controller/Public');
	}

	public function register_keywords_(){
		return $this->register_title_();
	}

	public function register_description_(){
		return $this->register_title_();
	}

	public function check_user(){
		$sUserName=trim(strtolower(G::getGpc('user_name')));

		$oUser=Dyhb::instance('UserModel');
		if($oUser->isUsernameExists($sUserName)===true){
			echo 'false';
		}else{
			echo 'true';
		}
	}

	public function check_email(){
		$sUserEmail=trim(strtolower(G::getGpc('user_email')));

		$oUser=Dyhb::instance('UserModel');
		if(!empty($sUserEmail) && $oUser->isUseremailExists($sUserEmail)===true){
			echo 'false';
		}else{
			echo 'true';
		}
	}

	public function register_user(){
		$sRefer=trim(G::getGpc('refer','P'));
		
		if($GLOBALS['___login___']!==false){
			$this->E(Dyhb::L('你已经登录会员,不能重复注册','Controller/Public'));
		}

		if($GLOBALS['_option_']['disallowed_register']){
			$this->E(Dyhb::L('系统关闭了用户注册','Controller/Public'));
		}

		if($GLOBALS['_option_']['seccode_register_status']==1){
			$this->_oParentcontroller->check_seccode(true);
		}

		$sPassword=trim(G::getGpc('user_password','P'));
		if(!$sPassword || $sPassword !=G::addslashes($sPassword)){
			$this->E(Dyhb::L('密码空或包含非法字符','Controller/Public'));
		}
		if(strpos($sPassword,"\n")!==false || strpos($sPassword,"\r")!==false || strpos($sPassword,"\t")!==false){
			$this->E(Dyhb::L('密码包含不可接受字符','Controller/Public'));
		}

		$sUsername=trim(G::getGpc('user_name','P'));
		$sDisallowedRegisterUser=trim($GLOBALS['_option_']['disallowed_register_user']);
		$sDisallowedRegisterUser='/^('.str_replace(array('\\*',"\r\n",' '),array('.*','|',''),preg_quote(($sDisallowedRegisterUser=trim($sDisallowedRegisterUser)),'/')).')$/i';
		if($sDisallowedRegisterUser && @preg_match($sDisallowedRegisterUser,$sUsername)){
			$this->E(Dyhb::L('用户名包含被系统屏蔽的字符','Controller/Public'));
		}

		$arrNameKeys=array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n",'#','$','(',')','%','@','+','?',';','^');
		foreach($arrNameKeys as $sNameKeys){
			if(strpos($sUsername,$sNameKeys)!==false){
				$this->E(Dyhb::L('此用户名包含不可接受字符或被管理员屏蔽,请选择其它用户名','Controller/Public'));
			}
		}

		$sUseremail=trim(G::getGpc('user_email','P'));
		$sDisallowedRegisterEmail=trim($GLOBALS['_option_']['disallowed_register_email']);
		if($sDisallowedRegisterEmail){
			$arrDisallowedRegisterEmail=explode("\n",$sDisallowedRegisterEmail);
			$arrDisallowedRegisterEmail=Dyhb::normalize($arrDisallowedRegisterEmail);
			if(in_array($sUseremail,$arrDisallowedRegisterEmail)){
				$this->E(Dyhb::L('你注册的邮件地址%s已经被官方屏蔽','Controller/Public',null,$sUseremail));
			}
		}

		$sAllowedRegisterEmail=trim($GLOBALS['_option_']['disallowed_register_email']);
		if($sAllowedRegisterEmail){
			$arrAllowedRegisterEmail=explode("\n",$sAllowedRegisterEmail);
			$arrAllowedRegisterEmail=Dyhb::normalize($arrAllowedRegisterEmail);
			if(!in_array($sUseremail,$arrAllowedRegisterEmail)){
				$this->E(Dyhb::L('你注册的邮件地址%s不在系统允许的邮件之列','Controller/Public',null,$sUseremail));
			}
		}

		$oUser=new UserModel();
		if($GLOBALS['_option_']['audit_register']==0){
			$oUser->user_status=1;
		}

		$oUser->save(0);
		if($oUser->isError()){
			$this->E($oUser->getErrorMessage());
		}else{
			$oUserprofile=new UserprofileModel();
			$oUserprofile->user_id=$oUser->user_id;
			$oUserprofile->save(0);

			if($oUserprofile->isError()){
				$oUserprofile->getErrorMessage();
			}

			$oUserCount=new UsercountModel();
			$oUserCount->user_id=$oUser->user_id;
			$oUserCount->save(0);

			if($oUserCount->isError()){
				$oUserCount->getErrorMessage();
			}

			// 将用户加入注册会员角色
			$oUserrole=new UserroleModel();
			$oUserrole->role_id=5;
			$oUserrole->user_id=$oUser['user_id'];
			$oUserrole->save(0);

			if($oUserrole->isError()){
				$oUserrole->getErrorMessage();
			}

			$this->cache_site_();

			// 注册推广
			$nCookiepromotion=Dyhb::cookie('_promotion_');
			if(!empty($nCookiepromotion) && $oUser['user_id']!=$nCookiepromotion){
				Core_Extend::updateCreditByAction('promotion_register',$nCookiepromotion);
				Dyhb::cookie('_promotion_',null,-1);
			}

			// 发送注册提醒
			$sNoticetemplate='<div class="notice_register"><div class="notice_content">'.str_replace(array('{static_time}','{static_user_name}'),array(date('Y-m-d H:i:s',CURRENT_TIMESTAMP),$oUser['user_name']),$GLOBALS['_option_']['register_welcome']).'</div></div>';
			$arrNoticedata=array();

			try{
				Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$oUser['user_id'],'system',0,0,'');
			}catch(Exception $e){
				$this->E($e->getMessage());
			}
			
			// 判断是否绑定社会化帐号
			if(G::getGpc('sociabind','P')==1){
				// 绑定社会化登录数据，以便于下次直接调用
				$oSociauser=Dyhb::instance('SociauserModel');
				$oSociauser->processBind($oUser['user_id']);

				if($oSociauser->isError()){
					$this->E($oSociauser->getErrorMessage());
				}

				$arrData=$oUser->toArray();
				$arrData['jumpurl']=Dyhb::U('home://public/sociabind_again');

				$arrSociauser=SociauserModel::F('user_id=?',$arrData['user_id'])->asArray()->getOne();
				Socia::setUser($arrSociauser);

				$this->A($arrData,Dyhb::L('绑定成功','Controller/Public'),1);

				exit();
			}
			
			if($sRefer==1 && !empty($_SERVER['HTTP_REFERER'])){
				$sJumpUrl=$_SERVER['HTTP_REFERER'];
			}elseif($sRefer){
				$sJumpUrl=$sRefer;
			}else{
				$sJumpUrl=Dyhb::U('home://public/login');
			}

			$arrData=$oUser->toArray();
			$arrData['jumpurl']=$sJumpUrl;

			$this->A($arrData,Dyhb::L('注册成功','Controller/Public'),1);
		}
	}

	protected function cache_site_(){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('site');
	}

}
