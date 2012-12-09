<?php
/* [DoYouHaoBaby!] (C)小牛哥Dyhb From 2010.10.04.
   使用MemCache来缓存数据($)*/

!defined('DYHB_PATH') && exit;

class MemcacheCache{

	protected $_arrDefaultServer=array(
		'host'=>'127.0.0.1',// 缓存服务器地址或主机名
		'port'=>'11211',// 缓存服务器端口
	);
	protected $_arrOptions=array(
		'servers'=>array(),
		'compressed'=>false,//是否压缩缓存数据
		'persistent'=>true,//是否使用持久连接
		'cache_time'=>86400
	);
	protected $_hHandel;

	public function __construct(array $arrOptions=null){
		if(!extension_loaded('memcache')){
			Dyhb::E('memcache extension must be loaded before use.');
		}

		if(!is_null($arrOptions)){
			$this->_arrOptions=array_merge($this->_arrOptions,$arrOptions);
		}

		if(empty($this->_arrOptions['servers'])){
			$this->_arrOptions['servers'][]=$this->_arrDefaultServer;
		}

		$this->_hHandel=new Memcache();
		foreach($this->_arrOptions['servers'] as $arrServer){
			$bResult=$this->_hHandel->addServer($arrServer['host'],$arrServer['port'],$this->_arrOptions['persistent']);
			if(!$bResult){
				Dyhb::E(sprintf('Unable to connect the memcached server [%s:%s] failed.',$arrServer['host'],$arrServer['port']));
			}
		}
	}

	public function getCache($sCacheName){
		return $this->_hHandel->get($sCacheName);
	}

	public function setCache($sCacheName,$Data,array $arrOptions=null){
		$bCompressed=isset($arrOptions['compressed'])?$arrOptions['compressed']:$this->_arrOptions['compressed'];
		$nCacheTime=isset($arrOptions['cache_time'])?$arrOptions['cache_time']:$this->_arrOptions['cache_time'];

		$this->_hHandel->set($sCacheName,$Data,$bCompressed?MEMCACHE_COMPRESSED:0,$nCacheTime);
	}

	public function deleleCache($sCacheName){
		return $this->_hHandel->delete($sCacheName);
	}

}
