<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   QQ互联处理类($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class VendorQq extends Vendor{

	const NAME='qq';
	protected $_oOauth=null;

	public function __construct(){
		parent::__construct(self::NAME);
		$this->_oOauth=new OauthQq();
	}

	public function login($sAppid,$sScope,$sCallback){
		$sState=md5(uniqid(rand(),TRUE));
		Dyhb::cookie('_socia_state_',$sState);

		$this->_oOauth->login($sAppid,$sScope,$sCallback,$sState);

		if($this->_oOauth->isError()){
			$this->setErrorMessage($this->_oOauth->getErrorMessage());
			return false;
		}
	}

	public function callback($sAppid,$sAppkey,$sCallback){
		$sCookieState=Dyhb::cookie('_socia_state_');
		$this->_oOauth->callback($sAppid,$sAppkey,$sCallback,$sCookieState);
		$this->getOpenid();

		if($this->_oOauth->isError()){
			$this->setErrorMessage($this->_oOauth->getErrorMessage());
			return false;
		}
	}

	public function getOpenid(){
		$this->_oOauth->getOpenid();

		if($this->_oOauth->isError()){
			$this->setErrorMessage($this->_oOauth->getErrorMessage());
			return false;
		}
	}

	public function getUserInfo($sAppid){
		if(($this->getAccessToken()===false)){
			return false;
		}

		$arrUser=$this->_oOauth->getUserInfo($sAppid);

		if($this->_oOauth->isError()){
			$this->setErrorMessage($this->_oOauth->getErrorMessage());
			return false;
		}

		return $arrUser;
	}

	public function gotoLoginPage(){
		$this->login($this->_sAppid,$this->_arrConfig['sociatype_scope'],$this->_sCallback);
	}

	public function getAccessToken(){
		return $this->callback($this->_sAppid,$this->_sSecid,$this->_sCallback);
	}

	public function showUser($keys=array()){
		$arrQquser=$this->getUserInfo($this->_sAppid);

		if($arrQquser && $arrQquser['ret']==0){
			$arrKeys=Socia::getKeys();

			$arrSaveData=array();
			$arrSaveData['sociauser_appid']=$this->_sAppid;
			$arrSaveData['sociauser_openid']=Dyhb::cookie('_socia_openid_');
			$arrSaveData['sociauser_vendor']=$this->_sVendor;
			$arrSaveData['sociauser_keys']=$this->_sSecid;
			$arrSaveData['sociauser_gender']=$arrQquser['gender'];
			$arrSaveData['sociauser_name']=$arrQquser['nickname'];
			$arrSaveData['sociauser_nikename']=$arrQquser['nickname'];
			$arrSaveData['sociauser_desc']=$arrQquser['msg'];
			$arrSaveData['sociauser_img']=$arrQquser['figureurl'];
			$arrSaveData['sociauser_img1']=$arrQquser['figureurl_1'];
			$arrSaveData['sociauser_img2']=$arrQquser['figureurl_2'];
			$arrSaveData['sociauser_vip']=$arrQquser['vip'];
			$arrSaveData['sociauser_level']=$arrQquser['level'];
			
			return $arrSaveData;
		}

		return FALSE;
	}

}
