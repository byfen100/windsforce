<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   互联入口类($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Socia{
	
	private $_oVendor;
	private $_oLocal;
	protected $_bIsError=false;
	protected $_sErrorMessage;

	public function __construct($sVendor=''){
		Core_Extend::loadCache('sociatype');
		
		$this->setVendor($sVendor);
		$this->setLocal();
	}

	public function setVendor($sVendor=''){
		if($sVendor){
			$sClass='Vendor'.ucfirst(strtolower($sVendor));
			$this->_oVendor=new $sClass();
		}
	}

	public function setLocal($sLocal='SociauserlocalController'){
		$this->_oLocal=new $sLocal();
	}
	
	static public function setUser($arrUser){
		Dyhb::cookie('SOCIAUSER',serialize($arrUser));
	}

	static public function getUser(){
		$sUser=Dyhb::cookie('SOCIAUSER');
		return !empty($sUser)?unserialize($sUser):FALSE;
	}

	public function login(){
		return $this->gotoLoginPage();
	}

	public function callback(){
		$arrUser=$this->_oVendor->getUser();
	
		if($this->_oVendor->isError()){
			$this->setErrorMessage($this->_oVendor->getErrorMessage());
			return false;
		}
		
		if($arrUser){
			self::setUser($arrUser);
		}

		// 标识社会化登录
		Dyhb::cookie('SOCIA_LOGIN',1);
		Dyhb::cookie('SOCIA_LOGIN_TYPE',$arrUser['sociauser_vendor']);

		return $arrUser;
	}

	public function gotoLoginPage(){
		$this->_oVendor->gotoLoginPage();

		if($this->_oVendor->isError()){
			$this->setErrorMessage($this->_oVendor->getErrorMessage());
			return false;
		}
	}

	public function bind(){
		if(!self::getUser()){
			if(!$this->isError()){
				$this->setErrorMessage('Can not find userinfo!');
			}
			return false;
		}

		$this->_oLocal->bind();
	}

	static public function clearCookie($bDeep=false){
		Dyhb::cookie('_socia_state_',NULL,-1);
		Dyhb::cookie('SOCIAUSER',NULL,-1);

		if($bDeep===true){
			Dyhb::cookie('SOCIA_LOGIN',NULL,-1);
			Dyhb::cookie('SOCIA_LOGIN_TYPE',NULL,-1);
			Dyhb::cookie("_socia_access_token_",NULL,-1);
			Dyhb::cookie('_socia_openid_',NULL,-1);
		}
	}

	protected function setIsError($bIsError=false){
		$bOldValue=$this->_bIsError;
		$this->_bIsError=$bIsError;

		return $bOldValue;
	}

	protected function setErrorMessage($sErrorMessage=''){
		$this->setIsError(true);
		$sOldValue=$this->_sErrorMessage;
		$this->_sErrorMessage=$sErrorMessage;

		return $sOldValue;
	}

	public function isError(){
		return $this->_bIsError;
	}

	public function getErrorMessage(){
		return $this->_sErrorMessage;
	}

}
