<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   WindsForce 安装函数库($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Install_Extend extends Controller{

	public static function gdVersion(){
		if(!function_exists('phpinfo')){
			if(function_exists('imagecreate')){
				return '2.0';
			}else{
				return 0;
			}
		}else{
			ob_start();
			phpinfo(8);

			$arrModuleInfo=ob_get_contents();
			ob_end_clean();
			if(preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i",$arrModuleInfo,$arrMatches)){
				$nGdversion=$arrMatches[1];
			}else{
				$nGdversion=0;
			}

			return $nGdversion;
		}
	}

	public static function testWrite($sPath){
		$sFile='Dyhb.test.txt';

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
	
	public static function showJavascriptMessage($sMessage){
		echo '<script type="text/javascript">showMessage(\''.addslashes($sMessage).' \');</script>'."\r\n";
		flush();
		ob_flush();
	}

	public static function importTable($sFilepath){
		global $hConn,$sSql4Tmp,$sDbprefix,$nMysqlVersion;

		$sQuery=$sTableName='';

		$hFp=fopen($sFilepath,'r');
		while(!feof($hFp)){
			$sLine=rtrim(fgets($hFp,1024));
			if(preg_match("#;$#",$sLine)){
				$sQuery.=$sLine."\n";
				$sQuery=str_replace('#@__',$sDbprefix,$sQuery);
				if(substr($sQuery,0,12)=='CREATE TABLE'){
					$sTableName=preg_replace("/CREATE TABLE `([a-z0-9_]+)` .*/is","\\1",$sQuery);
				}

				if($nMysqlVersion<4.1){
					self::queryString($sQuery);
				}else{
					if(preg_match('#CREATE#i',$sQuery)){
						self::queryString(preg_replace("#TYPE=MyISAM#i",$sSql4Tmp,$sQuery));
					}else{
						self::queryString($sQuery);
					}
				}

				self::showJavascriptMessage(Dyhb::L('创建数据库表','Function/Install_Extend').' '.$sTableName.' ... '.Dyhb::L('成功','Function/Install_Extend'));

				$sQuery='';
			}else if(!preg_match("#^(\/\/|--)#",$sLine)){
				$sQuery.=$sLine;
			}
		}
		fclose($hFp);
	}

	public static function runQuery($sFilepath,$bEchomessage=true){
		global $hConn,$sSql4Tmp,$sDbprefix,$nMysqlVersion;
		
		$sQuery='';

		$hFp=fopen($sFilepath,'r');
		while(!feof($hFp)){
			$sLine=rtrim(fgets($hFp,1024));
			if(preg_match("#;$#",$sLine)){
				$sQuery.=$sLine;
				$sQuery=str_replace('#@__',$sDbprefix,$sQuery);
				self::queryString($sQuery);

				if($bEchomessage===true){
					self::showJavascriptMessage(
						Dyhb::L('执行SQL','Function/Install_Extend').' '.G::subString($sQuery,0,50).' ... '.Dyhb::L('成功','Function/Install_Extend'));
				}

				$sQuery='';
			}else if(!preg_match("#^(\/\/|--)#",$sLine)){
				$sQuery.=$sLine;
			}
		}

		fclose($hFp);
	}

	static public function queryString($sSql){
		global $hConn;

		$hRs=mysql_query($sSql,$hConn);

		if($hRs){
			return $hRs;
		}else{
			self::sqlError($sSql);
		}
	}

	static public function sqlError($sSql){
		self::showJavascriptMessage('');
		self::showJavascriptMessage('<h3 style="color:red;">'.Dyhb::L('对不起数据库执行遇到错误','Function/Install_Extend').'</h3>');

		self::showJavascriptMessage('<b>'.Dyhb::L('错误代码','Function/Install_Extend').'</b>');
		self::showJavascriptMessage(mysql_errno());
		self::showJavascriptMessage('');
		self::showJavascriptMessage('<b>'.Dyhb::L('错误消息','Function/Install_Extend')).'</b>';
		self::showJavascriptMessage(mysql_error());
		self::showJavascriptMessage('');
		self::showJavascriptMessage('<b>'.Dyhb::L('错误SQL','Function/Install_Extend')).'</b>';
		self::showJavascriptMessage($sSql);
		self::showJavascriptMessage('');
		self::showJavascriptMessage('<b>'.Dyhb::L('请修正后再次执行升级程序','Function/Install_Extend').'</b>');

		exit();
	}

	static public function removeDir($sDirName){
		if(!is_dir($sDirName)){
			@unlink($sDirName);
			self::showJavascriptMessage(Dyhb::L('清理文件','Function/Install_Extend').' '.str_replace(G::tidyPath(WINDSFORCE_PATH),'{WINDSFORCE_PATH}',G::tidyPath($sDirName)));

			return false;
		}

		$hHandle=@opendir($sDirName);
		while(($file=@readdir($hHandle))!==false){
			if($file!='.' && $file!='..'){
				$sDir=$sDirName.'/'.$file;
				if(is_dir($sDir)){
					self::removeDir($sDir);
				}else{
					@unlink($sDir);
					self::showJavascriptMessage(Dyhb::L('清理文件','Function/Install_Extend').' '.str_replace(G::tidyPath(WINDSFORCE_PATH),'{WINDSFORCE_PATH}',G::tidyPath($sDir)));
				}
			}
		}

		closedir($hHandle);

		$bResult=rmdir($sDirName);
		self::showJavascriptMessage(Dyhb::L('清理目录','Function/Install_Extend').' '.str_replace(G::tidyPath(WINDSFORCE_PATH),'{WINDSFORCE_PATH}',G::tidyPath($sDirName)));

		return $bResult;
	}

	public static function isEmptydir($sDir){
		$hDir=@opendir($sDir);
		
		$nI=0;
		while($file=readdir($hDir)){
			$nI++;
		}
		closedir($hDir);

		if($nI>2){
			return false;
		}else{
			return true;
		}
	}

}
