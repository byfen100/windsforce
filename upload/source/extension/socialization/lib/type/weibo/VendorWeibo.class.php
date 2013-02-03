<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   Sina微博处理类($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class VendorWeibo extends Vendor{

	const NAME='weibo';
	protected $_oOauth=null;

	public function __construct(){
		parent::__construct(self::NAME);
		new OauthWeibo();
		$this->_oOauth=new SaeTOAuthV2($this->_arrConfig['sociatype_appid'],$this->_arrConfig['sociatype_appkey']);
	}

	public function login($sAppid,$sScope,$sCallback){
		$sState=md5(uniqid(rand(),TRUE));
		Dyhb::cookie('_socia_state_',$sState);
		
		try{
			$sUrl=$this->_oOauth->getAuthorizeURL($this->_arrConfig['sociatype_callback']);
			G::urlGoTo($sUrl);
		}catch(OAuthException $e){
			$this->setErrorMessage($e->getMessage());
			return false;
		}
	}

	public function callback($sAppid,$sAppkey,$sCallback){
		$sCookieState=Dyhb::cookie('_socia_state_');
		
		if(isset($_REQUEST['code'])){
			$arrKeys= array();
			$arrKeys['code']=$_REQUEST['code'];
			$arrKeys['redirect_uri']=$this->_arrConfig['sociatype_callback'];
			
			try{
				$arrToken=$this->_oOauth->getAccessToken('code',$arrKeys);

				if(isset($arrToken['error_code']) && $arrToken['error_code']>0){
					$sErrorMessage="<h5>Error:</h5>".$arrToken['error_code'];
					$sErrorMessage.="<h5>Msg :</h5>".$arrToken['error'];

					$this->setErrorMessage($sErrorMessage);
					return false;
				}
			}catch(OAuthException $e){
				$this->setErrorMessage($e->getMessage());
				return false;
			}
		}

		if($arrToken){
			Dyhb::cookie("_socia_access_token_",$arrToken);
			Dyhb::cookie('weibojs_'.$this->_oOauth->client_id,http_build_query($arrToken));
		}
	}

	public function getUserInfo($sAppid){
		if(($this->getAccessToken()===false)){
			return false;
		}
		
		try{
			$arrToken=Dyhb::cookie("_socia_access_token_");

			$oClient=new SaeTClientV2($this->_arrConfig['sociatype_appid'],$this->_arrConfig['sociatype_appkey'],$arrToken['access_token']);
			$ms=$oClient->home_timeline();
			$arrUidget=$oClient->get_uid();

			if(isset($arrUidget['error_code']) && $arrUidget['error_code']>0){
				$sErrorMessage="<h5>Error:</h5>".$arrUidget['error_code'];
				$sErrorMessage.="<h5>Msg :</h5>".$arrUidget['error'];

				$this->setErrorMessage($sErrorMessage);
				return false;
			}
			
			$nUid=$arrUidget['uid'];
			$arrUserMessage=$oClient->show_user_by_id($nUid);

			return $arrUserMessage;
		}catch(OAuthException $e){
			$this->setErrorMessage($e->getMessage());
			return false;
		}
	}

	public function gotoLoginPage(){
		$this->login($this->_sAppid,$this->_arrConfig['sociatype_scope'],$this->_sCallback);
	}

	public function getAccessToken(){
		return $this->callback($this->_sAppid,$this->_sSecid,$this->_sCallback);
	}

	public function showUser($keys=array()){
		$arrWeibouser=$this->getUserInfo($this->_sAppid);

		if($arrWeibouser && $arrWeibouser['id']){
			$arrKeys=Socia::getKeys();

			$arrSaveData=array();
			$arrSaveData['sociauser_appid']=$this->_sAppid;
			$arrSaveData['sociauser_openid']=$arrWeibouser['id'];
			$arrSaveData['sociauser_vendor']=$this->_sVendor;
			$arrSaveData['sociauser_keys']=$this->_sSecid;
			$arrSaveData['sociauser_gender']=$arrWeibouser['gender']=='m'?'男':'女';
			$arrSaveData['sociauser_name']=$arrWeibouser['name'];
			$arrSaveData['sociauser_nikename']=$arrWeibouser['screen_name'];
			$arrSaveData['sociauser_desc']=$arrWeibouser['description'];
			$arrSaveData['sociauser_img']=$arrWeibouser['profile_image_url'];
			$arrSaveData['sociauser_img1']=$arrWeibouser['avatar_large'];
			$arrSaveData['sociauser_img2']=$arrWeibouser['avatar_large'];
			$arrSaveData['sociauser_vip']='0';
			$arrSaveData['sociauser_level']='0';
			
			return $arrSaveData;
		}

		return FALSE;
	}

}
