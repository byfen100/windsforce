<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   缓存文件($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Cache_Extend{

	public static function updateCache($sCacheName='',$arrNotallowed=array()){
		$arrUpdateList=empty($sCacheName)?array():(is_array($sCacheName)?$sCacheName:array($sCacheName));

		if(!$arrUpdateList){
			$arrUpdatecache=array();

			$arrAllCachefile=G::listDir(WINDSFORCE_PATH.'/source/function/cache',false,true);
			foreach($arrAllCachefile as $sCachefile){
				$sCachefile=strtolower(subStr($sCachefile,11,-5));
				if(!in_array($sCachefile,$arrNotallowed)){
					$arrUpdatecache[]=$sCachefile;
				}
			}

			foreach($arrUpdatecache as $sUpdatecache){
				self::updateCache($sUpdatecache);
			}
		}else{
			foreach($arrUpdateList as $sCache){
				$sCachefile=WINDSFORCE_PATH.'/source/function/cache/UpdateCache'.ucfirst($sCache).'_.php';

				if(is_file($sCachefile)){
					$sCacheclass='UpdateCache'.ucfirst($sCache);
					if(!Dyhb::classExists($sCacheclass)){
						require_once(Core_Extend::includeFile('function/cache/'.$sCacheclass,null,'_.php'));
					}

					$Callback=array($sCacheclass,'cache');
					if(is_callable($Callback)){
						call_user_func($Callback);
					}else{
						Dyhb::E('$Callback is not a callback');
					}
				}else{
					if(strpos($sCache,'_')!==false){
						$arrCaches=explode('_',$sCache);

						self::appUpdateCache(strtolower($arrCaches[1]),strtolower($arrCaches[0]),$arrNotallowed);
					}else{
						Dyhb::E('cache parameter is error');
					}
				}
			}
		}
	}

	public static function appUpdateCache($sCacheName='',$sApp='home',$arrNotallowed=array()){
		$arrUpdateList=empty($sCacheName)?array():(is_array($sCacheName)?$sCacheName:array($sCacheName));

		if(!$arrUpdateList){
			$arrUpdatecache=array();

			$arrAllCachefile=G::listDir(WINDSFORCE_PATH.'/app/'.$sApp.'/App/Class/Extension/cache',false,true);
			foreach($arrAllCachefile as $sCachefile){
				$sCachefile=strtolower(subStr($sCachefile,14,-5));
				if(!in_array($sCachefile,$arrNotallowed)){
					$arrUpdatecache[]=$sCachefile;
				}
			}

			foreach($arrUpdatecache as $sUpdatecache){
				self::appUpdateCache($sUpdatecache);
			}
		}else{
			foreach($arrUpdateList as $sCache){
				$sCachefile=WINDSFORCE_PATH.'/app/'.$sApp.'/App/Class/Extension/cache/AppUpdateCache'.ucfirst($sCache).'_.php';

				if(is_file($sCachefile)){
					$sCacheclass='AppUpdateCache'.ucfirst($sCache);
					if(!Dyhb::classExists($sCacheclass)){
						require_once(Core_Extend::includeFile('cache/'.$sCacheclass,$sApp,'_.php'));
					}

					$Callback=array($sCacheclass,'cache');
					if(is_callable($Callback)){
						call_user_func($Callback);
					}else{
						Dyhb::E('$Callback is not a callback');
					}
				}else{
					Dyhb::E('$sCachefile %s is not exists');
				}
			}
		}
	}

}
