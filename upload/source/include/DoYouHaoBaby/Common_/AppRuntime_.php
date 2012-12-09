<?php
/* [DoYouHaoBaby!] (C)小牛哥Dyhb From 2010.10.04.
   项目编译类($)*/

!defined('DYHB_PATH') && exit;

class AppRuntime{

	public function run(){
		if(!is_writeable(APP_PATH)){
			E('Project directory is not writable,the directory can not be automatically generated! Build the project manually compiled directory');
		}

		if(!is_dir(APP_RUNTIME_PATH) || !is_file(APP_RUNTIME_LOCK)){
			if(!is_dir(APP_RUNTIME_PATH)){
				if(!$this->makeDir(APP_RUNTIME_PATH,0777)){
					E(sprintf('Dir:%s made failed!',APP_RUNTIME_PATH));
				}
			}

			if(!file_put_contents(APP_RUNTIME_LOCK,'App Lock! Do Not To Remove This File or The App Dirs Will Rebuild !')){
				E(sprintf('File:%s write failed!',APP_RUNTIME_LOCK));
			}
			$this->buildApp();
		}else{
			$this->checkRuntime();
		}

		$this->combileRuntime();

		return;
	}

	private function combileRuntime(){
		$bRuntimeRemove=!defined('RUNTIME_NOT_REMOVE');
		
		$sContent='';
		if(defined('DYHB_THIN') && DYHB_THIN===true){
			$sContent.=$this->compileAFile(DYHB_PATH.'/Base.php',$bRuntimeRemove);
		}

		$arrList=array();// 编译加载文件
		$arrList=array_merge($arrList,(array)(include DYHB_PATH.'/Config_/Paths.inc.php'));
		foreach($arrList as $sFile){
			$sContent.=$this->compileAFile($sFile,$bRuntimeRemove);
		}
		
		if(defined('STRIP_RUNTIME_SPACE') && STRIP_RUNTIME_SPACE===false){
			if(!file_put_contents(DYHB_PATH.'/~DoYouHaoBaby.php','<?php'.$sContent)){
				E(sprintf('File:%s write failed!',DYHB_PATH.'/~DoYouHaoBaby.php'));
			};
		}else{
			if(!file_put_contents(DYHB_PATH.'/~DoYouHaoBaby.php',$this->stripWhitespace('<?php'.$sContent))){
				E(sprintf('File:%s write failed!',DYHB_PATH.'/~DoYouHaoBaby.php'));
			};
		}
		unset($sContent);

		echo 'App Runtime File Build Succeed! Please refresh!<br/>';
	}

	private function compileAFile($sFile,$bRuntimeRemove=false){
		$sContent=file_get_contents($sFile);
		if(true===$bRuntimeRemove){
			$sContent=preg_replace('/\/\/\[RUNTIME\](.*?)\/\/\[\/RUNTIME\]/s','',$sContent);
		}

		$sContent=substr(trim($sContent),5);
		$sContent=str_replace('!defined(\'DYHB_PATH\') && exit;','',$sContent);
		
		$sBrCode=IS_WIN?"\r\n":"\n";
		$sContent=str_replace(array("\r","\n"),'__dyhb_framework_pk_with_you__',$sContent);
		$sContent=preg_replace("/(__dyhb_framework_pk_with_you__)+/i",'__dyhb_framework_pk_with_you__',$sContent);
		$sContent=str_replace('__dyhb_framework_pk_with_you__',$sBrCode,$sContent);
		
		if('?>'==substr($sContent,-2))
		$sContent=substr($sContent,0,-2);

		return $sContent;
	}

	private function stripWhitespace($sContent){
		$sStripStr='';

		$arrTokens=token_get_all($sContent);// 分析php源码
		$bLastSpace=false;
		for($nI=0,$nJ=count($arrTokens);$nI<$nJ;$nI++){
			if(is_string($arrTokens[$nI])){
				$bLastSpace=false;
				$sStripStr.=$arrTokens[$nI];
			}else{
				switch($arrTokens[$nI][0]){
					case T_COMMENT: //过滤各种PHP注释
					case T_DOC_COMMENT:
						break;
					case T_WHITESPACE:// 过滤空格
						if(!$bLastSpace){
							$sStripStr.=' ';
							$bLastSpace=true;
						}
						break;
					default:
						$bLastSpace=false;
						$sStripStr.=$arrTokens[$nI][1];
				}
			}
		}

		return $sStripStr;
	}

	private function buildApp(){
		$arrError=array();

		if(!defined('APP_NAME')){
			define('APP_NAME',basename(APP_PATH));
		}
		
		$sAppName=preg_replace('[^a-z0-9_]','',APP_NAME);// 过滤App_name中的非法字符
		if(!$sAppName){
			$arrError[]=sprintf('The application name(%s) invalid.',$sAppName);
		}

		$sAppPath=dirname(APP_PATH);
		$sReallyPath=realpath($sAppPath);
		if(!$sAppPath || $sReallyPath==dirname(__FILE__) || !is_dir($sReallyPath)){
			$arrError[]=sprintf('Project directory name(%s) invalid.',$sAppPath);
		}else{
			$sAppPath=$sReallyPath;
		}

		if(empty($arrError)){
			require dirname(dirname(dirname(__FILE__))).'/Tools/Tools_/App/Generator/GeneratorApplication_.php';
			
			ob_start();
			$sTestAppDir=null;
			$oApp=new GeneratorApplication();
			$oApp->APP($sAppName,$sAppPath,null,$sTestAppDir,true);
			$sOutput=ob_get_clean();
			
			$sAppPath=$sAppName='';
			E(nl2br(htmlspecialchars($sOutput)));

			return;
		}else{
			E(implode('<br/>',$arrError));
		}
	}

	private function checkRuntime(){
		if(!is_writeable(APP_RUNTIME_PATH)){
			E('Dir [ '.APP_RUNTIME_PATH.' ] Can not write!');
		}

		if(!is_dir(APP_RUNTIME_PATH.'/Temp')){ 
			mkdir(APP_RUNTIME_PATH.'/Temp',0777);
		}
		if(!is_dir(APP_RUNTIME_PATH.'/Data')){ 
			mkdir(APP_RUNTIME_PATH.'/Data',0777);
		}
		if(!is_dir(APP_RUNTIME_PATH.'/Cache')){ 
			mkdir(APP_RUNTIME_PATH.'/Cache',0777);
		}
		if(!is_dir(APP_RUNTIME_PATH.'/Log')){
			mkdir(APP_RUNTIME_PATH.'/Log',0777);
		}

		return true;
	}

	private function makeDir($Dir,$nMode=0777){
		if(is_dir($Dir)){
			return true;
		}

		if(is_string($Dir)){
			$arrDirs=explode('/',str_replace('\\','/',trim($Dir,'/')));
		}else{
			$arrDirs=$Dir;
		}

		$sMakeDir=IS_WIN?'':'/';
		foreach($arrDirs as $sDir){
			$sMakeDir.=$sDir.'/';
			!is_dir($sMakeDir) && mkdir($sMakeDir,$nMode);
		}

		return TRUE;
	}

}
