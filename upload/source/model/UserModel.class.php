<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UserModel extends CommonModel{

	static public function init__(){

		return array(
			'behaviors'=>'rbac',
			'behaviors_settings'=>array(
				'rbac'=>array(
					'password_prop'=>'user_password',
					'rbac_data_props'=>'user_id,user_name,user_lastlogintime,user_lastloginip,
										user_logincount,user_email,user_remark,create_dateline,user_registerip,
										update_dateline,user_status,user_nikename,user_isverify,user_extendstyle'
				),
			),
			'table_name'=>'user',
			'props'=>array(
				'user_id'=>array('readonly'=>true),
				'user_name'=>array('readonly'=>true),
				'userprofile'=>array(Db::HAS_ONE=>'UserprofileModel','source_key'=>'user_id','target_key'=>'user_id'),
				'usercount'=>array(Db::HAS_ONE=>'UsercountModel','source_key'=>'user_id','target_key'=>'user_id'),
			),

			'attr_protected'=>'user_id',
			'check'=>array(
				'user_email'=>array(
					array('require',Dyhb::L('E-mail不能为空','__COMMON_LANG__@Model/User')),
					array('email',Dyhb::L('E-mail格式错误','__COMMON_LANG__@Model/User')),
					array('max_length',150,Dyhb::L('E-mail不能超过150个字符','__COMMON_LANG__@Model/User'))
				),
				'user_name'=>array(
					array('require',Dyhb::L('用户名不能为空','__COMMON_LANG__@Model/User')),
				),
				'user_password'=>array(
					array('require',Dyhb::L('用户密码不能为空','__COMMON_LANG__@Model/User')),
					array('min_length',6,Dyhb::L('用户密码最小长度为6个字符','__COMMON_LANG__@Model/User')),
					array('max_length',32,Dyhb::L('用户密码最大长度为32个字符','__COMMON_LANG__@Model/User')),
				),
				'user_sign'=>array(
					array('empty'),
					array('max_length',1000,Dyhb::L('用户签名最大长度为1000个字符','__COMMON_LANG__@Model/User')),
				),
			),
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}

	public function isUsernameExists($sUsername,$nUserId=0){
		$oResult=self::F()->getByuser_name($sUsername);

		if(!empty($oResult['user_id'])){
			if($nUserId==0){
				return true;
			}else{
				if($oResult['user_id']==$nUserId){
					return false;
				}else{
					return true;
				}
			}
		}else{
			return false;
		}
	}

	public function isUseremailExists($sUseremail,$nUserId=0){
		$oResult=self::F()->getByuser_email($sUseremail);

		if(!empty($oResult['user_id'])){
			if($nUserId==0){
				return true;
			}else{
				if($oResult['user_id']==$nUserId){
					return false;
				}else{
					return true;
				}
			}
		}else{
			return false;
		}
	}

	public function changePassword($sPassword,$sNewPassword,$sOldPassword,$bIgnoreOldPassword=false,$arrUserData=array(),$bResetPassword=false){
		if(empty($arrUserData) && $bResetPassword===false){
			$arrUserData=$GLOBALS['___login___'];
		}

		if($bIgnoreOldPassword===false && $sOldPassword==''){
			$this->setErrorMessage(Dyhb::L('旧密码不能为空','__COMMON_LANG__@Model/User'));
		}

		if($sPassword==''){
			$this->setErrorMessage(Dyhb::L('新密码不能为空','__COMMON_LANG__@Model/User'));
		}

		if($sPassword!=$sNewPassword){
			$this->setErrorMessage(Dyhb::L('两次输入的密码不一致','__COMMON_LANG__@Model/User'));
		}

		UserModel::M()->changePassword($arrUserData['user_name'],$sPassword,$sOldPassword,$bIgnoreOldPassword);
		if(UserModel::M()->isBehaviorError()){
			$this->setErrorMessage(UserModel::M()->getBehaviorErrorMessage());
		}else{
			if($bResetPassword===false){
				$oUser=UserModel::F('user_id=?',$arrUserData['user_id'])->query();
				if($oUser->isError()){
					$this->setErrorMessage($oUser->getErrorMessage());
				}

				$arrUserData=$GLOBALS['___login___'];
				UserModel::M()->replaceSession($arrUserData['session_hash'],$arrUserData['user_id'],$arrUserData['session_auth_key']);
				UserModel::M()->logout();
				UserModel::M()->clearThisCookie();
			}
		}

		return true;
	}

	public function checkLoginCommon($sUserName,$sPassword,$bEmail,$sApp='admin'){
		// 是否记住登陆状态
		$nLoginCookieTime=null;
		if(G::getGpc('remember_me','P')){
			if(G::getGpc('remember_time','P')==0){
				$nLoginCookieTime=null;
			}else{
				$nLoginCookieTime=intval(G::getGpc('remember_time','P'));
			}
		}

		UserModel::M()->initLoginlife($nLoginCookieTime);
		$oUser=UserModel::M()->checkLogin($sUserName,$sPassword,$bEmail);

		if($GLOBALS['_option_']['loginlog_record']==1){
			$oLoginlog=new LoginlogModel();
			$oLoginlog->loginlog_username=$sUserName;
			$oLoginlog->login_application=$sApp;
		}

		if($GLOBALS['_option_']['loginlog_record']==1 && G::isImplementedTo($oUser,'IModel')){
			$oLoginlog->user_id=$oUser->user_id;
		}

		if(UserModel::M()->isBehaviorError()){
			if($GLOBALS['_option_']['loginlog_record']==1){
				$oLoginlog->loginlog_status=0;
				$oLoginlog->save(0);
			}

			$this->setErrorMessage(UserModel::M()->getBehaviorErrorMessage());
			return false;
		}else{
			if($oUser->isError()){
				if($GLOBALS['_option_']['loginlog_record']==1){
					$oLoginlog->loginlog_status=0;
					$oLoginlog->save(0);
				}

				$this->setErrorMessage($oUser->getErrorMessage());
				return false;
			}

			if($GLOBALS['_option_']['loginlog_record']==1){
				$oLoginlog->loginlog_status=1;
				$oLoginlog->save(0);

				if($oLoginlog->isError()){
					$this->setErrorMessage($oLoginlog->getErrorMessage());
					return false;
				}
			}
		}

		return true;
	}

	public function safeInput(){
		if(isset($_POST['user_name'])){
			$_POST['user_name']=G::html($_POST['user_name']);
		}

		if(isset($_POST['user_nikename'])){
			$_POST['user_nikename']=G::html($_POST['user_nikename']);
		}

		if(isset($_POST['user_remark'])){
			$_POST['user_remark']=G::cleanJs($_POST['user_remark']);
		}

		if(isset($_POST['user_sign'])){
			$_POST['user_sign']=G::cleanJs($_POST['user_sign']);
		}
	}

	static public function getUsernameById($nUserId,$sField='user_name'){
		$oUser=UserModel::F('user_id=?',$nUserId)->query();

		if(empty($oUser['user_id'])){
			return Dyhb::L('佚名','__COMMON_LANG__@Model/User');
		}
		
		return $oUser[$sField];
	}

	static public function getUserrating($nScore,$bOnlyname=true){
		Core_Extend::loadCache('rating');
		
		foreach($GLOBALS['_cache_']['rating'] as $nKey=>$arrRating){
			if($nScore>=$arrRating['rating_creditstart'] && $nScore<=$arrRating['rating_creditend']){
				if($bOnlyname===true){
					return $arrRating['rating_name'];
				}else{
					$arrRating['next_rating']=$GLOBALS['_cache_']['rating'][$nKey+1];
					$arrRating['next_needscore']=$arrRating['rating_creditend']-$nScore;
					$arrRating['next_progress']=number_format(($nScore-$arrRating['rating_creditstart'])/($arrRating['rating_creditend']-$arrRating['rating_creditstart']),2)*100;

					return $arrRating;
				}
			}
		}
	}

}
