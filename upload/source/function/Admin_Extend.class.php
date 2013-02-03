<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   后台相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

@set_time_limit(0);

class Admin_Extend{

	static public function sortField($sName){
		Core_Extend::sortField($sName);
	}
	
	static public function base($arrPars=array()){
		return Dyhb::U("app/config?id=".$_GET['id'],$arrPars);
	}

	static public function soryBy($sField,$sSort,$arrPars=array()){
		return Dyhb::U("app/config?id=".$_GET['id']."&order_={$sField}&sort_={$sSort}",$arrPars);
	}

	static public function edit($arrPars=array()){
		return Dyhb::U("app/config?action=edit&id=".$_GET['id'],$arrPars);
	}
	
	static public function delete($arrPars=array()){
		return Dyhb::U("app/config?action=foreverdelete&id=".$_GET['id'],$arrPars);
	}
	
	static public function insert($arrPars=array()){
		return Dyhb::U("app/config?action=insert&id=".$_GET['id'],$arrPars);
	}
	
	static public function update($arrPars=array()){
		return Dyhb::U("app/config?action=update&id=".$_GET['id'],$arrPars);
	}
	
	static public function add($arrPars=array()){
		return Dyhb::U("app/config?action=add&id=".$_GET['id'],$arrPars);
	}
	
	static public function index($arrPars=array()){
		return Dyhb::U("app/config?id=".$_GET['id'],$arrPars);
	}

	static public function runQuery($sSql){
		$sTabelPrefix=$GLOBALS['_commonConfig_']['DB_PREFIX'];
		$sDbCharset=$GLOBALS['_commonConfig_']['DB_CHAR'];

		$sSql=str_replace(array(' windsforce_',' `windsforce_',' prefix_',' `prefix_'),array(' {WINDSFORCE}',' `{WINDSFORCE}',' {WINDSFORCE}',' `{WINDSFORCE}'),$sSql);
		$sSql=str_replace("\r","\n",str_replace(array(' {WINDSFORCE}',' `{WINDSFORCE}'),array(' '.$sTabelPrefix,' `'.$sTabelPrefix),$sSql));

		$arrResult=array();
		$nNum=0;
		foreach(explode(";\n",trim($sSql)) as $sQuery){
			$arrQueries=explode("\n",trim($sQuery));
			foreach($arrQueries as $sQuery){
				if(isset($arrResult[$nNum])){
					$arrResult[$nNum].=(isset($sQuery[0]) && $sQuery[0]=='#') || (isset($sQuery[0]) && isset($sQuery[1]) && $sQuery[0].$sQuery[1]=='--')?'':$sQuery;
				}else{
					$arrResult[$nNum]=(isset($sQuery[0]) && $sQuery[0]=='#') || (isset($sQuery[0]) && isset($sQuery[1]) && $sQuery[0].$sQuery[1]=='--')?'':$sQuery;
				}
			}
			$nNum++;
		}
		unset($sSql);

		$oDb=Db::RUN();
		foreach($arrResult as $sQuery){
			$sQuery=trim($sQuery);
			if($sQuery){
				if(substr($sQuery,0,12)=='CREATE TABLE'){
					$sName=preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1",$sQuery);
					$oDb->query(self::createTable($sQuery,$sDbCharset));
				}else{
					$oDb->query($sQuery);
				}
			}
		}
	}

	public function createTable($sSql,$sDbCharset){
		$sType=strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2",$sSql));
		$sType=in_array($sType,array('MYISAM','HEAP'))?$sType:'MYISAM';

		return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU","\\1",$sSql).(mysql_get_server_info()>'4.1'?" ENGINE={$sType} DEFAULT CHARSET={$sDbCharset}":" TYPE={$sType}");
	}

	static public function testWrite($sPath){
		$sFile='WindsForce.test.txt';

		$sPath=preg_replace("#\/$#",'',$sPath);

		$hFp=@fopen($sPath.'/'.$sFile,'w');
		if(!$hFp){
			return false;
		}else{
			fclose($hFp);

			$bRs=@unlink($sPath.'/'.$sFile);
			if($bRs){
				return true;
			}else{
				return false;
			}
		}
	}

	static public function template($sApp,$sTemplate,$sTheme=null){
		if(empty($sTheme)){
			$sTemplate=TEMPLATE_NAME.'/'.$sTemplate;
		}else{
			$sTemplate=$sTheme.'/'.$sTemplate;
		}

		$sUrl=WINDSFORCE_PATH.'/app/'.$sApp.'/Theme/Admin/'.$sTemplate.'.html';

		if(is_file($sUrl)){
			return $sUrl;
		}

		if(defined('DOYOUHAOBABY_TEMPLATE_BASE') && empty($sTheme) && ucfirst(DOYOUHAOBABY_TEMPLATE_BASE)!==TEMPLATE_NAME){// 依赖模板 兼容性分析
			$sUrlTry=str_replace('/Theme/Admin/'.TEMPLATE_NAME.'/','/Theme/Admin/'.ucfirst(DOYOUHAOBABY_TEMPLATE_BASE).'/',$sUrl);
			if(is_file($sUrlTry)){
				return $sUrlTry;
			}
		}

		if(empty($sTheme) && 'Default'!==TEMPLATE_NAME){// Default模板 兼容性分析
			$sUrlTry=str_replace('/Theme/Admin/'.TEMPLATE_NAME.'/','/Theme/Admin/Default/',$sUrl);
			if(is_file($sUrlTry)){
				return $sUrlTry;
			}
		}

		Dyhb::E(sprintf('Template File %s is not exist',$sUrl));
	}

}
