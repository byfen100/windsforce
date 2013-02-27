<?php
/* [$DoYouHaoBaby] (C)WindsForce TEAM Since 2010.10.04.
   对 PHP 原生Cookie 函数库的封装($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Cookie{

	public static function setCookie($sName,$sValue='',$nLife=0,$bPrefix=true,$bHttponly=false){
		$sName=($bPrefix?$GLOBALS['_commonConfig_']['COOKIE_PREFIX']:'').$sName;

		if($sValue=='' || $nLife<0){
			$sValue='';
			$nLife=-1;
			if(isset($_COOKIE[$sName])){
				unset($_COOKIE[$sName]);
			}
		}else{
			$sValue=base64_encode(serialize($sValue));
			$_COOKIE[$sName]=$sValue;
			if($nLife!==NULL && $nLife==0){
				$nLife=$GLOBALS['_commonConfig_']['COOKIE_EXPIRE'];
			}
		}

		$nLife=$nLife>0?CURRENT_TIMESTAMP+$nLife:($nLife<0?CURRENT_TIMESTAMP-31536000:null);
		$sPath=$bHttponly && PHP_VERSION<'5.2.0'?$GLOBALS['_commonConfig_']['COOKIE_PATH'].';HttpOnly':$GLOBALS['_commonConfig_']['COOKIE_PATH'];
	
		$nSecure=$_SERVER['SERVER_PORT']==443?1:0;
		if(PHP_VERSION<'5.2.0'){
			setcookie($sName,$sValue,$nLife,$sPath,$GLOBALS['_commonConfig_']['COOKIE_DOMAIN'],$nSecure);
		}else{
			setcookie($sName,$sValue,$nLife,$sPath,$GLOBALS['_commonConfig_']['COOKIE_DOMAIN'],$nSecure,$bHttponly);
		}
	}

	public static function getCookie($sName,$bPrefix=true){
		$sName=($bPrefix?$GLOBALS['_commonConfig_']['COOKIE_PREFIX']:'').$sName;

		return isset($_COOKIE[$sName])?unserialize(base64_decode($_COOKIE[$sName])):'';
	}

	public static function deleteCookie($sName,$bPrefix=true){
		self::setCookie($sName,null,-1,$bPrefix);
	}

	public static function clearCookie($bOnlyDeletePrefix=true){
		$nCookie=count($_COOKIE);
		foreach($_COOKIE as $sKey=>$Val){
			if($bOnlyDeletePrefix===true && $GLOBALS['_commonConfig_']['COOKIE_PREFIX']){
				if(strpos($sKey,$GLOBALS['_commonConfig_']['COOKIE_PREFIX'])===0){
					self::setCookie($sKey,null,-1);
				}
			}else{
				self::setCookie($sKey,null,-1);
			}
		}

		return $nCookie;
	}

}
