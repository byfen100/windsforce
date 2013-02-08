<?php
/* [DoYouHaoBaby!] (C)WindsForce Team Start This From 2010.10.04.
   使用Apc来缓存数据($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ApcCache{

	protected $_arrOptions=array(
		'cache_time'=>86400
	);

	public function __construct(array $arrOptions=null){
		if(isset($arrOptions['cache_time'])){
			$this->_arrOptions['cache_time']=(int)$arrOptions['cache_time'];
		}
	}

	public function getCache($sCacheName){
		return apc_fetch($sCacheName);
	}

	public function setCache($sCacheName,$Data,array $arrOptions=null){
		$nCacheTime=!isset($arrOptions['cache_time'])?(int)$arrOptions['cache_time']:$this->_arrOptions['cache_time'];
		
		apc_store($sCacheName,$Data,$nCacheTime);
	}

	public function deleleCache($sCacheName){
		apc_delete($sCacheName);
	}

}
