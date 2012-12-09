<?php
/* [DoYouHaoBaby!] (C)小牛哥Dyhb From 2010.10.04.
   使用Shmop来缓存数据($)*/

!defined('DYHB_PATH') && exit;

class ShmopCache extends DCache{

	protected $_arrOptions=array(
		'project'=>'s',
		'cache_path'=>'',
		'size'=>1024
	);
	protected $_hHandel=null;

	public function __construct(array $arrOptions=null){
		if(!extension_loaded('shmop')){
			Dyhb::E('shmop extension must be loaded before use.');
		}

		if(!is_null($arrOptions)){
			$this->_arrOptions=array_merge($this->_arrOptions,$arrOptions);
		}
		
		$this->_hHandler=$this->ftok($this->_arrOptions['project']);

		if(empty($this->_arrOptions['cache_path'])){
			$this->_arrOptions['cache_path']=APP_RUNTIME_PATH.'/Data';
		}

		if(!is_dir($this->_arrOptions['cache_path'])){
			G::makeDir($this->_arrOptions['cache_path']);
		}
	}

	public function getCache($sCacheName){
		$nId=shmop_open($this->_hHandler,'c',0600,0);

		if($nId!==false){
			$arrRet=unserialize(shmop_read($nId,0,shmop_size($nId)));
			shmop_close($nId);
			if($sCacheName==''){
				return $arrRet;
			}

			if(isset($arrRet[$sCacheName])){
				$sContent=$arrRet[$sCacheName];
				if(function_exists('gzcompress')){
					$sContent=gzuncompress($sContent);
				}

				return $sContent;
			}else{
				return null;
			}
		}else{
			return false;
		}
	}

	public function setCache($sCacheName,$Data){
		$hLH=$this->lock();

		$arrVal=$this->get('');
		if(!is_array($arrVal)){
			$arrVal=array();
		}

		if(function_exists('gzcompress')) {
			$Data=gzcompress($Data,3);
		}

		$arrVal[$sCacheName]=$Data;
		$sVal=serialize($arrVal);

		return $this->write($sVal,$hLH);
	}

	public function deleleCache($sCacheName){
		$hLh=$this->lock();

		$arrVal=$this->get('');
		if(!is_array($arrVal)){
			$arrVal=array();
		}
		unset($arrVal[$sCacheName]);
		$sVal=serialize($arrVal);

		return $this->write($sVal,$hLh);
	}

	private function ftok($sProject){
		if(function_exists('ftok')){
			return ftok(__FILE__,$sProject);
		}

		if(strtoupper(PHP_OS)=='WINNT'){
			$arrS=stat(__FILE__);
			
			return sprintf("%u",(($arrS['ino']&0xffff)|(($arrS['dev']&0xff)<<16)|(($sProject&0xff)<<24)));
		}else{
			$sFileName=__FILE__.(string)$sProject;
			for($arrKey=array();sizeof($arrKey)<strlen($sFileName);$arrKey[]=ord(substr($sFileName,sizeof($arrKey),1)));
			
			return dechex(array_sum($arrKey));
		}
	}

	private function write($sVal,$hLH){
		$nId=shmop_open($this->_hHandler,'c',0600,$this->_arrOptions['size']);

		if($nId){
			$bRet=shmop_write($nId,$sVal,0)==strlen($sVal);
			shmop_close($nId);
			$this->unLock($hLH);

			return $bRet;
		}

		$this->unLock($hLH);

		return false;
	}

	private function lock(){
		if(function_exists('sem_get')){
			$hFp=sem_get($this->_hHandler,1,0600,1);
			sem_acquire($hFp);
		}else{
			$hFp=fopen($this->_arrOptions['cache_path'].'/'.$this->_sPrefix.md5($this->_hHandler),'w');
			flock($hFp,LOCK_EX);
		}

		return $hFp;
	}

	private function unLock($hFp){
		if(function_exists('sem_release')){
			sem_release($hFp);
		}else{
			fclose($hFp);
		}
	}

}
