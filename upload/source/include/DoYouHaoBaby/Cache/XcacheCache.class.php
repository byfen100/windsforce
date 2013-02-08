<?php
/* [DoYouHaoBaby!] (C)WindsForce Team Start This From 2010.10.04.
   使用XCache来缓存数据($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class XcacheCache{

	protected $_arrOptions=array(
		'cache_time'=>86400
	);

	public function __construct(array $arrOptions=null){
		if(isset($arrOptions['cache_time'])){
			$this->_arrOptions['cache_time']=(int)$arrOptions['cache_time'];
		}
	}

	public function getCache($sCacheName){
		if(xcache_isset($sCacheName)){
			return xcache_get($sCacheName);
		}

		return false;
	}

	public function setCache($sCacheName,$Data,array $arrOptions=null){
		$nCacheTime=!isset($arrOptions['cache_time'])?(int)$arrOptions['cache_time']:$this->_arrOptions['cache_time'];

		xcache_set($sCacheName,$Data,$nCacheTime);
	}

	public function deleleCache($sCacheName){
		xcache_unset($sCacheName);
	}

}
