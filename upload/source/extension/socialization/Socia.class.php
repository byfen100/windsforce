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

		$sRand=Dyhb::cookie('SOCIAUSERTEMP');
		if(!$sRand){
			$sRand=G::randString(12);
			Dyhb::cookie('SOCIAUSERTEMP',$sRand);
		}
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
		$sRand=Dyhb::cookie('SOCIAUSERTEMP');

		Core_Extend::saveSyscache('sociauser'.$sRand,$arrUser);
	}

	static public function getUser(){
		$sRand=Dyhb::cookie('SOCIAUSERTEMP');
		
		if(!isset($GLOBALS['_cache_']['sociauser'.$sRand])){
			Core_Extend::loadCache('sociauser'.$sRand,false,'db');
		}
		$arrUser=$GLOBALS['_cache_']['sociauser'.$sRand];

		return !empty($arrUser)?$arrUser:FALSE;
	}

	static public function setKeys($arrKeys){
		Dyhb::cookie('SOCIAKEYS',$arrKeys);
	}

	static public function getKeys(){
		$arrKeys=Dyhb::cookie('SOCIAKEYS');
		return !empty($arrKeys)?$arrKeys:FALSE;
	}
	
	public function login(){
		return $this->gotoLoginPage();
	}

	public function callback(){
		$arrKeys=self::getKeys();
		
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
		self::clearCookie(true);
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

	static public function clearCookie($bSelf=false){
		$sRand=Dyhb::cookie('SOCIAUSERTEMP');
		$oSyscache=Dyhb::instance('SyscacheModel');

		$oSyscache->delCache('sociauser'.$sRand);
		$oSyscache->delCache('sociastate'.$sRand);
		$oSyscache->delCache('sociaaccesstoken'.$sRand);
		$oSyscache->delCache('sociaopenid'.$sRand);

		if($bSelf===false){
			Dyhb::cookie('SOCIAUSERTEMP',null,-1);
		}

		Dyhb::cookie('SOCIAKEYS',null,-1);
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
