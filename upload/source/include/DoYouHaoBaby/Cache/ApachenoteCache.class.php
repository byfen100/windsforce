<?php
/* [$DoYouHaoBaby] (C)WindsForce TEAM Since 2010.10.04.
   使用Apachenote来缓存数据($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ApachenoteCache{

	protected $_arrOptions=array(
		'host'=>'127.0.0.1',
		'port'=>1042,
		'timeout'=>10
	);
	protected $_hHandel=null;
	protected $_bConnected=false;

	public function __construct(array $arrOptions=null){
		if(!is_null($arrOptions)){
			$this->_arrOptions=array_merge($this->_arrOptions,$arrOptions);
		}
	}

	public function getCache($sCacheName,array $arrOptions=null){
		$arrOptions=$this->option($arrOptions);

		$this->open($arrOptions);

		$sS='F'.pack('N',strlen($sCacheName)).$sCacheName;
		fwrite($this->_hHandler,$sS);
		for($Data='';!feof($this->_hHandler);){
			$Data.=fread($this->_hHandler,4096);
		}

		$this->close();

		return $Data===''?'':unserialize($Data);
	}

	public function setCache($sCacheName,$Data,array $arrOptions=null){
		$arrOptions=$this->option($arrOptions);

		$this->open($arrOptions);

		$Data=serialize($Data);
		$sS='S'.pack('NN',strlen($sCacheName),strlen($Data)).$sCacheName.$Data;
		fwrite($this->_hHandler,$sS);
		$sRet=fgets($this->_hHandler);

		$this->close();

		$this->setTime[$sCacheName]=time();

		return $sRet==="OK\n";
	}

	public function deleleCache($sCacheName,array $arrOptions=null){
		$arrOptions=$this->option($arrOptions);

		$this->open($arrOptions);

		$sS='D'.pack('N',strlen($sCacheName)).$sCacheName;
		fwrite($this->_hHandler,$sS);
		$sRet=fgets($this->_hHandler);

		$this->close();

		return $sRet==="OK\n";
	}

	public function isConnected(){
		return $this->_bConnected;
	}

	protected function open($arrOptions=array()){
		if(!is_resource($this->_hHandler)){
			$this->_hHandler=fsockopen($arrOptions['host'],$arrOptions['port'],$_,$_,$arrOptions['timeout']);
			$this->_bConnected=is_resource($this->_hHandler);
		}
	}

	protected function close(){
		fclose($this->_hHandler);
		$this->_hHandler=null;
	}

	protected function option(array $arrOptions=null){
		return !is_null($arrOptions)?array_merge($this->_arrOptions,$arrOptions):$this->_arrOptions;
	}

}
