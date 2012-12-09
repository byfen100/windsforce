<?php
/* [DoYouHaoBaby!] (C)小牛哥Dyhb From 2010.10.04.
   DoYouHaoBaby 基础初始化文件($)*/

/** 系统异常和错误处理 */
set_exception_handler(array('Dyhb','exceptionHandler'));

if(!defined('NEEDFORBUG_DEBUG')){
	define('NEEDFORBUG_DEBUG',FALSE);
}
if(NEEDFORBUG_DEBUG===TRUE){
	set_error_handler(array('Dyhb','errorHandel'));
	register_shutdown_function(array('Dyhb','shutdownHandel'));
}

/** 自动载入 */
if(function_exists('spl_autoload_register')) {
	spl_autoload_register(array('Dyhb','autoload'));
}else{
	function __autoload($sClassName){
		Dyhb::autoLoad($sClassName);
	}
}

/** 编译锁定文件 */
if(!defined('APP_RUNTIME_LOCK')){
	define('APP_RUNTIME_LOCK',APP_RUNTIME_PATH.'/~Runtime.inc.lock');
}
if(!is_file(APP_RUNTIME_LOCK)){
	require(DYHB_PATH.'/Common_/InitRuntime.inc.php');
}

/** DoYouHaoBaby框架定义 | 本版本于2012/10/23发布 */
define('DYHB_VERSION','2.1');

/** 定义内存 */
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
if(MEMORY_LIMIT_ON){
	$GLOBALS['_startUseMems_']=memory_get_usage();
}

/** CURRENT_TIMESTAMP 定义为当前时间，减少框架调用 time()的次数 */
define('CURRENT_TIMESTAMP',time());

/** PHP魔术方法 */
if(version_compare(PHP_VERSION,'6.0.0','<')){
	if(version_compare(PHP_VERSION,'5.3.0','<')){
		@set_magic_quotes_runtime(0);
	}

	define('MAGIC_QUOTES_GPC',get_magic_quotes_gpc()?TRUE:FALSE);
}

class Dyhb{

	static private $INSTANCES=array();
	static private $OBJECTS=array();
	static private $_arrClassRegex=array('/^(.+)\.class\.php$/i','/^(.+)\.interface\.php$/i');
	static private $_arrClassFilePat=array('%DirPath%/%ClassName%.class.php');
	static private $_arrInterPat=array('%DirPath%/%ClassName%.interface.php');
	static private $CLASS_PATH='Class.inc';
	static private $_arrImportedPackDir=array();
	static private $_bAutoLoad=true;
	static private $_sPackagePath='';
	static private $_arrConfig=array();

	static public function import($sPackage,$bForce=false){
		if(!is_dir($sPackage)){
			Dyhb::E("Package:'{$sPackage}' does not exists.");
		}

		// 包路径
		self::$_sPackagePath=$sPackagePath=realpath($sPackage).'/';
		$sClassPathFile=$sPackagePath.self::$CLASS_PATH;

		if($bForce || !is_file($sClassPathFile)){
			$arrFileStores=array();

			// 扫描类
			$arrClassPath=self::viewClass($sPackagePath);
			foreach($arrClassPath as $arrMap){
				$arrFileStores[$arrMap['class']]=$arrMap['file'];
				$arrKeys[]=$arrMap['class'];
			}

			// 检查是否有重复的类
			if(!empty($arrKeys) && count($arrKeys)!=count(array_unique($arrKeys))){
				$arrDiffKeys=array();
				$arrDiffUnique=array_unique($arrKeys);
				foreach($arrDiffUnique as $nKey=>$sValue){
					if(in_array($sValue,$arrKeys)){
						unset($arrKeys[$nKey]);
					}
				}

				E(sprintf('Same class %s exists',implode(',',$arrKeys)));
			}

			foreach($arrFileStores as $nKeyFileStore=>$sFileStoreValue){
				if(in_array(DYHB_PATH.'/'.$sFileStoreValue,(array)(include DYHB_PATH.'/Config_/Paths.inc.php'))){
					unset($arrFileStores[$nKeyFileStore]);
				}
			}

			$sFileContents=serialize($arrFileStores);

			// 类路径文件
			if(!is_file($sClassPathFile)){
				if(($hFile=fopen($sClassPathFile,'a'))!==false){
					fclose($hFile);
					chmod($sClassPathFile,0666);
				}else{
					return false;
				}
			}

			// 写入文件
			if(!file_put_contents($sClassPathFile,$sFileContents)){
				E(sprintf('Can not create Class Path File: %s',$sClassPathFile));
			}
		}

		// 读取Classes Path文件
		self::$OBJECTS=array_merge(self::$OBJECTS,array_map(array('Dyhb','reallyPath'),self::readCache($sClassPathFile)));
	}

	static public function reallyPath($sValue){
		return self::$_sPackagePath.$sValue;
	}

	static public function readCache($sCacheFile){
		$sData=file_get_contents($sCacheFile);

		return unserialize($sData);
	}

	static public function regClass($sClass,$sPath){
		if(isset(self::$OBJECTS[$sClass])){
			E(sprintf('Class %s already exist in %s and unable to repeat the register',$sClass,$sPath));
		}

		self::$OBJECTS[$sClass]=$sPath;
	}

	static public function setAutoload($bAutoload){
		if(!is_bool($bAutoload)){
			$bAutoload=$bAutoload?true:false;
		}else{
			$bAutoload=&$bAutoload;
		}

		$bOldValue=self::$_bAutoLoad;
		self::$_bAutoLoad=$bAutoload;

		return $bOldValue;
	}

	static public function autoLoad($sClassName){
		if(isset(self::$OBJECTS[$sClassName]) && !self::classExists($sClassName) && !self::classExists($sClassName,true)){
			require(self::$OBJECTS[$sClassName]);
		}
	}

	static public function classExists($sClassName,$bInter=false,$bAutoload=false){
		$bAutoloadOld=self::setAutoload($bAutoload);

		$sFuncName=$bInter?'interface_exists':'class_exists';
		$bResult=$sFuncName($sClassName);
		self::setAutoload($bAutoloadOld);

		return $bResult;
	}

	static private function viewClass($sDirectory,$sPreFilename=''){
		$arrReturnClass=array();

		$sDirectoryPath=realpath($sDirectory).'/';
		$hDir=opendir($sDirectoryPath);

		while(($sFilename=readdir($hDir))!==false){
			$sPath=$sDirectoryPath.$sFilename;
			if(is_file($sPath)){// 文件
				foreach(self::$_arrClassRegex as $sRegexp){
					$arrRes=array();// 找到类文件
					if(preg_match($sRegexp,$sFilename,$arrRes)){
						$sClassName=isset($arrRes[1])?$arrRes[1]:null;
						if($sClassName){
							$arrReturnClass[]=array('class'=>$sClassName,'file'=>$sPreFilename.$sFilename);
						}
					}
				}
			}else if(is_dir($sPath)){// 目录
				$sSpecialDir=array('.','..','.svn','#note');
				if(in_array($sFilename,$sSpecialDir)){// 排除特殊目录
					unset($sSpecialDir);
					continue;
				}else{// 递归子目录
					$arrReturnClass=array_merge($arrReturnClass,self::viewClass($sPath,$sPreFilename.$sFilename.'/'));
				}
			}else{
				Dyhb::E(sprintf("\$sPath:%s is not a valid path",$sPath));
			}
		}

		return $arrReturnClass;
	}

	//[RUNTIME]
	static public function importJsPackage($sPackage,$bRePack=false){
		$sPackagePath=str_replace('.','/',$sPackage);
		$sIoPath=preg_replace('/^Dyhb/',DYHB_PATH.'/Resource_/Js',$sPackagePath);
		$sPackagePath=$sIoPath.'/'.basename( $sPackagePath ).'.package.js';

		if(!is_dir($sIoPath)){
			return;
		}

		if($bRePack || !is_file($sPackagePath)){
			self::packupJs($sIoPath,$sPackagePath);
		}

		if(is_file($sPackagePath)){
			return ;
		}
	}

	static private function packupJs($sDir,$sPackagePath){
		$sPackOrderFile=$sDir.'/Pack.order.php';

		if(is_file($sPackOrderFile)){
			$sPackOrder=file_get_contents($sPackOrderFile);
			$sPackOrder=str_replace("\r",'',$sPackOrder);
			$arrPackOrder=explode("\n",$sPackOrder);
		}else{
			$arrPackOrder=array();
		}

		// 开始历遍包内的文件
		$hFiles=opendir($sDir);
		while(($sFileName=readdir($hFiles))!==false){
			$sPath="{$sDir}/{$sFileName}";
			if(is_file($sPath)){// 文件
				if(!in_array($sFileName,$arrPackOrder)){
					if(preg_match('/^.+\.(class|interface)\.js$/i',$sFileName)){
						$arrPackOrder[]=$sFileName;
					}
				}
			}
		}

		// 打包
		$hPackage=fopen($sPackagePath,'w');
		chmod($sPackagePath,0666);
		foreach($arrPackOrder as $sFileName){
			if(!$sFileName){
				continue;
			}
			$sFilePath=$sDir.'/'.$sFileName;
			if(is_file($sFilePath)){
				$sContent=file_get_contents($sFilePath);
				$sContent=trim(preg_replace(array('/(^|\r|\n)\/\*.+?(\r|\n)\*\/(\r|\n)/is','/\/\/note.+?(\r|\n)/i','/\/\/debug.+?(\r|\n)/i','/(^|\r|\n)(\s|\t)+/','/(\r|\n)/',"/\/\*(.*?)\*\//ies"),'',$sContent));
				fwrite($hPackage,$sContent);
			}else{
				fwrite($hPackage,sprintf("throw new Error ('missing JavaScript file:%s!');",$sFilePath));
			}
		}
		fclose($hPackage);
	}
	//[/RUNTIME]

	static public function instance($sClass,$Args=null,$sMethod=null,$MethodArgs=null){
		$sIdentify=$sClass.serialize($Args).$sMethod.serialize($MethodArgs);// 惟一识别号

		if(!isset(self::$INSTANCES[$sIdentify])){
			if(class_exists($sClass)){
				$oClass=$Args===null?new $sClass():new $sClass($Args);
				if(!empty($sMethod) && method_exists($oClass,$sMethod)){
					self::$INSTANCES[$sIdentify]=$MethodArgs===null?call_user_func(array(&$oClass,$sMethod)):call_user_func_array(array(&$oClass,$sMethod),array($MethodArgs));
				}else{
					self::$INSTANCES[$sIdentify]=$oClass;
				}
			}else{
				Dyhb::E(sprintf('class %s is not exists',$sClass));
			}
		}

		return self::$INSTANCES[$sIdentify];
	}

	static public function cache($sId,$Data='',array $arrOption=null,$sBackendClass=null){
		static $oObj=null;

		if(is_null($sBackendClass)){
			$sBackendClass=$GLOBALS['_commonConfig_']['RUNTIME_CACHE_BACKEND'];
		}

		if(is_null($oObj)){
			$oObj=self::instance($sBackendClass);
		}

		if($Data===''){
			return $oObj->getCache($sId,$arrOption);
		}
		if($Data===null){
			return $oObj->deleleCache($sId,$arrOption);
		}
		return $oObj->setCache($sId,$Data,$arrOption);
	}

	public static function normalize($Input,$sDelimiter=',',$bAllowedEmpty=false){
		if(is_array($Input) || is_string($Input)){
			if(!is_array($Input)){
				$Input=explode($sDelimiter,$Input);
			}

			$Input=array_filter($Input);// 过滤null
			if($bAllowedEmpty===true){
				return $Input;
			}else{
				$Input=array_map('trim',$Input);
				return array_filter($Input,'strlen');
			}
		}else{
			return $Input;
		}
	}

	static public function exceptionHandler(Exception $oE){
		$sErrstr=$oE->getMessage();
		$sErrfile=$oE->getFile();
		$nErrline=$oE->getLine();
		$nErrno=$oE->getCode();
		$sErrorStr="[$nErrno] $sErrstr ".basename($sErrfile).self::L(" 第 %d 行。",'__DYHB__@LibDyhb',null,$nErrline);

		if($GLOBALS['_commonConfig_']['LOG_RECORD'] && self::C('LOG_MUST_RECORD_EXCEPTION')){
			Log::W($sErrstr,Log::EXCEPTION);
		}

		if(method_exists($oE,'formatException')){
			self::halt($oE->formatException());
		}else{
			self::halt($oE->getMessage());
		}
	}

	static public function errorHandel($nErrorNo,$sErrStr,$sErrFile,$nErrLine){
		if($nErrorNo){
			E("<b>[{$nErrorNo}]:</b> {$sErrStr}<br><b>File:</b> {$sErrFile}<br><b>Line:</b> {$nErrLine}");
		}
	}

	static public function shutdownHandel(){
		if(($arrError=error_get_last()) && $arrError['type']){
			E("<b>[{$arrError['type']}]:</b> {$arrError['message']}<br><b>File:</b> {$arrError['file']}<br><b>Line:</b> {$arrError['line']}");
		}
	}

	static public function C($sName='',$Value=NULL,$Default=null){
		// 时返回配置数据
		if(is_string($sName) && !empty($sName) && $Value===null){
			if(!strpos($sName,'.')){
				return array_key_exists($sName,self::$_arrConfig)?self::$_arrConfig[$sName]:$Default;
			}

			$arrParts=explode('.',$sName);
			$arrConfig=&self::$_arrConfig;
			foreach($arrParts as $sPart){
				if(!isset($arrConfig[$sPart])){
					return $Default;
				}
				$arrConfig=&$arrConfig[$sPart];
			}

			return $arrConfig;
		}

		// 返回所有配置值
		if($sName==='' && $Value===null){
			return self::$_arrConfig;
		}

		// 设置值
		if(is_array($sName)){
			foreach($sName as $sKey=>$value){
				self::C($sKey,$value,$Default);
			}

			return self::$_arrConfig;
		}else{
			if(!strpos($sName,'.')){
				self::$_arrConfig[$sName]=$Value;

				return;
			}

			$arrParts=explode('.',$sName);
			$nMax=count($arrParts)-1;
			$arrConfig=&self::$_arrConfig;
			for($nI=0;$nI<=$nMax;$nI++){
				$sPart=$arrParts[$nI];

				if($nI<$nMax){
					if(!isset($arrConfig[$sPart])){
						$arrConfig[$sPart]=array();
					}

					$arrConfig=&$arrConfig[$sPart];
				}else{
					$arrConfig[$sPart]=$Value;
				}
			}

			return self::$_arrConfig;
		}

		// 删除值
		if($sName===null){
			self::$_arrConfig=array();
		}elseif(!strpos($sName,'.')){
			unset(self::$_arrConfig[$sName]);
		}else{
			$arrParts=explode('.',$sName);
			$nMax=count($arrParts)-1;
			$arrConfig=&self::$_arrConfig;
			for($nI=0;$nI<=$nMax;$nI++){
				$sPart=$arrParts[$nI];

				if($nI<$nMax){
					if(!isset($arrConfig[$sPart])){
						$arrConfig[$sPart]=array();
					}

					$arrConfig=&$arrConfig[$sPart];
				}else{
					unset($arrConfig[$sPart]);
				}
			}
		}

		return self::$_arrConfig;
	}

	static public function throwException($sMsg,$sType='DException',$nCode=0){
		if($sType==''||$sType===null){
			$sType='DException';
		}

		if(Dyhb::classExists($sType)){
			throw new $sType($sMsg,$nCode);
		}else{
			self::halt($sMsg);// 异常类型不存在则输出错误信息字串
		}
	}

	static public function E($sMsg,$sType='DException',$nCode=0){
		self::throwException($sMsg,$sType,$nCode);
	}

	static public function L($sValue,$Package=null,$Lang=null/*Argvs*/){
		$arrArgvs=func_get_args();

		if(!isset($arrArgvs[1]) OR empty($Package)){
			$arrArgvs[1]='app';
		}
		if(!isset($arrArgvs[2])){
			if(!defined('LANG_NAME')){
				$arrArgvs[2]='Zh-cn';
			}else{
				$arrArgvs[2]=LANG_NAME;
			}
		}
		$sValue=call_user_func_array(array('Lang','setEx'),$arrArgvs);

		return $sValue;
	}

	static public function W($sName,$Data='',$bReturn=FALSE){
		$sClass=ucwords(strtolower($sName)).'Widget';

		if(!Dyhb::classExists($sClass)){
			self::E(self::L('类不存在：%s','__DYHB__@Dyhb',null,$sClass));
		}

		$oWidget=Dyhb::instance($sClass);
		if(is_string($Data)){
			parse_str($Data,$Data);
		}

		$sContent=$oWidget->render($Data);
		if($bReturn){
			return $sContent;
		}else{
			echo $sContent;
		}
	}

	static public function cookie($sName,$Value='',$nLife=0,$bPrefix=true,$bHttponly=false,$bOnlyDeletePrefix=true){
		// 清除指定前缀的所有cookie
		if(is_null($sName)){
			if(empty($_COOKIE)){ 
				return;
			}
			Cookie::clearCookie($bOnlyDeletePrefix);
			return;
		}

		// 如果值为null，则删除指定COOKIE
		if($nLife<0 || $Value===null){
			Cookie::deleteCookie($sName,$bPrefix);
		}elseif($Value=='' && $nLife>=0){// 如果值为空，则获取cookie
			return Cookie::getCookie($sName,$bPrefix);
		}else{// 设置COOKIE
			Cookie::setCookie($sName,$Value,$nLife,$bPrefix,$bHttponly);
		}
	}

	static public function U($sUrl,$arrParams=array(),$bRedirect=false,$bSuffix=true){
		$sUrl=ltrim($sUrl,'\\/');
	
		if(!strpos($sUrl,'://')){
			$sUrl=APP_NAME.'://'.$sUrl;
		}
		
		if(stripos($sUrl,'@?')){
			$sUrl=str_replace('@?','@doyouhaobaby?',$sUrl);
		}elseif(stripos($sUrl,'@')){
			$sUrl=$sUrl.MODULE_NAME;
		}

		// app && 路由
		$arrArray=parse_url($sUrl);
		$sApp=isset($arrArray['scheme'])?$arrArray['scheme']:APP_NAME;// APP
		$sRoute=isset($arrArray['user'])?$arrArray['user']:'';// 路由

		// 分析获取模块和操作
		if(isset($arrArray['path'])){
			$sAction=substr($arrArray['path'],1);

			if(!isset($arrArray['host'])){
				$sModule=MODULE_NAME;
			}else{
				$sModule=$arrArray['host'];
			}
		}else{
			$sModule=MODULE_NAME;
			$sAction=$arrArray['host'];
		}

		// 如果指定了查询参数
		if(isset($arrArray['query'])){
			$arrQuery=array();
			parse_str($arrArray['query'],$arrQuery);
			$arrParams=array_merge($arrQuery,$arrParams);
		}
		
		// 如果开启了URL解析，则URL模式为非普通模式
		if($GLOBALS['_commonConfig_']['URL_MODEL']>0){
			$sDepr=$GLOBALS['_commonConfig_']['URL_PATHINFO_MODEL']==2?$GLOBALS['_commonConfig_']['URL_PATHINFO_DEPR']:'/';
			
			if(!empty($sRoute)){
				// 匹配路由参数
				if(isset($GLOBALS['_commonConfig_']['_ROUTER_'][$sRoute])){
					$arrRouters=$GLOBALS['_commonConfig_']['_ROUTER_'][$sRoute];
					if(!empty($arrRouters[1])){
						$arrRoutervalue=explode(',',$arrRouters[1]);
						foreach($arrRoutervalue as $sRoutervalue){
							if(array_key_exists($sRoutervalue,$arrParams)){
								$sRoute.=$sDepr.urlencode($arrParams[$sRoutervalue]);
								unset($arrParams[$sRoutervalue]);
							}
						}
					}
				}

				$sStr=$sDepr;
				if(is_array($arrParams)){
					foreach($arrParams as $sVar=>$sVal){
						$sStr.=$sVar.$sDepr.urlencode($sVal).$sDepr;
					}
				}
				$sStr=substr($sStr,0,-1);

				$sUrl=(__APP__!=='/'?__APP__:'').($GLOBALS['_commonConfig_']['DEFAULT_APP']!=$sApp?$sDepr.'app'.$sDepr.$sApp:'').$sDepr.$sRoute.$sStr;
			}else{
				$sStr=$sDepr;
				if(is_array($arrParams)){
					foreach($arrParams as $sVar=>$sVal){
						$sStr.=$sVar.$sDepr.urlencode($sVal).$sDepr;
					}
				}
				$sStr=substr($sStr,0,-1);

				$sUrl=(__APP__!=='/'?__APP__:'').($GLOBALS['_commonConfig_']['DEFAULT_APP']!=$sApp?$sDepr.'app'.$sDepr.$sApp:'').$sDepr.$sModule.$sDepr.$sAction.$sStr;
			}

			if($bSuffix && $GLOBALS['_commonConfig_']['URL_HTML_SUFFIX']){
				$sUrl.=$GLOBALS['_commonConfig_']['URL_HTML_SUFFIX'];
			}
		}else{
			$sStr='';
			if(is_array($arrParams)){
				foreach($arrParams as $sVar=>$sVal){
					$sStr.=$sVar.'='.urlencode($sVal).'&';
				}
			}
			$sStr=rtrim($sStr,'&');

			if(empty($sRoute)){
				$sUrl=(__APP__!=='/'?__APP__:'').($GLOBALS['_commonConfig_']['DEFAULT_APP']!=$sApp?'?app='.$sApp.'&':'?').'c='.$sModule.'&a='.$sAction.($sStr?'&'.$sStr:'');
			}else{
				$sUrl=(__APP__!=='/'?__APP__:'').($GLOBALS['_commonConfig_']['DEFAULT_APP']!=$sApp?'?app='.$sApp.'&':'?').($sRoute?'r='.$sRoute:'').($sStr?'&'.$sStr:'');
			}
		}

		$sUrl=$GLOBALS['_commonConfig_']['URL_DOMAIN'].$sUrl;

		if($bRedirect){
			G::urlGoTo($sUrl);
		}else{
			return $sUrl;
		}
	}

	static public function halt($Error){
		$arrError=array();

		if(is_array($Error)){
			$arrError=array_merge($arrError,$Error);
		}

		// 否则定向到错误页面
		$sErrorPage=$GLOBALS['_commonConfig_']['ERROR_PAGE']?DYHB_PATH.'/Resource_/Template/'.$GLOBALS['_commonConfig_']['ERROR_PAGE'].".template.php":'';
		if(!empty($sErrorPage)){
			G::urlGoTo($sErrorPage);
		}else{
			if($GLOBALS['_commonConfig_']['SHOW_ERROR_MSG']){
				$arrError['message']=is_array($Error)?$Error['message']:$Error;
			}else{
				$arrError['message']='Error';
			}

			include(DYHB_PATH.'/Resource_/Template/DException.template.php');// 包含异常页面模板
		}

		exit;
	}

}
