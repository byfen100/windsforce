<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   缓存文件($)*/

!defined('DYHB_PATH') && exit;

class Cache_Extend{

	public static function updateCache($sCacheName='',$arrNotallowed=array()){
		$arrUpdateList=empty($sCacheName)?array():(is_array($sCacheName)?$sCacheName:array($sCacheName));

		if(!$arrUpdateList){
			$arrUpdatecache=array();

			$arrAllCachefile=G::listDir(NEEDFORBUG_PATH.'/source/function/cache',false,true);
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
				$sCachefile=NEEDFORBUG_PATH.'/source/function/cache/UpdateCache'.ucfirst($sCache).'_.php';

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
					$arrCaches=explode('_',$sCache);
					$Callback=array(ucfirst($arrCaches[0]).'Cache_Extend','updateCache'.ucfirst($arrCaches[1]));

					if(is_callable($Callback)){
						call_user_func($Callback);
					}else{
						Dyhb::E('$Callback is not a callback');
					}
				}
			}
		}
	}

}
