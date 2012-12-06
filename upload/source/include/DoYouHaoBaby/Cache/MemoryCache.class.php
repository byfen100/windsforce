<?php
/* [DoYouHaoBaby!] (C)Dianniu From 2010.
   使用内存进行缓存($)*/

!defined('DYHB_PATH') && exit;

class MemoryCache{

	public function __construct(){}

	public function getCache($sCacheName){
		return $this->existCache($sCacheName)?self::$CACHES[$sCacheName]:false;
	}

	public function setCache($sCacheName,$Data){
		self::$CACHES[$sCacheName]=$Data;
	}

	public function deleleCache($sCacheName){
		if($this->existCache($sCacheName)){
			unset(self::$CACHES[$sCacheName]);
		}
	}

	public function existCache($sCacheName){
		return isset(self::$CACHES[$sCacheName]);
	}

}
