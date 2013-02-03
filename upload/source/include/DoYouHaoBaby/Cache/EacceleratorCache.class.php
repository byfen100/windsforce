<?php
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   使用Eaccelerator来缓存数据($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class EacceleratorCache{

	protected $_arrOptions=array(
		'cache_time'=>86400
	);
	
	public function __construct(array $arrOptions=null){
		if(isset($arrOptions['cache_time'])){
			$this->_arrOptions['cache_time']=(int)$arrOptions['cache_time'];
		}
	}

	public function getCache($sCacheName){
		return eaccelerator_get($sCacheName);
	}

	 public function setCache($sCacheName,$Data,array $arrOptions=null){
		$nCacheTime=!isset($arrOptions['cache_time'])?(int)$arrOptions['cache_time']:$this->_arrOptions['cache_time'];

		eaccelerator_lock($sCacheName);

		return eaccelerator_put($sCacheName,$Data,$nCacheTime);
	}

	public function deleleCache($sCacheName){
		return eaccelerator_rm($sCacheName);
	}

}
