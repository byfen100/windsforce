<?php
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   DoYouHaoBaby 框架基础文件($)*/
/** 防止乱码 */
header("Content-type:text/html;charset=utf-8");
/** DoYouHaoBaby系统目录定义 */
	define('DYHB_PATH',str_replace('\\','/',dirname(__FILE__)));
/** 系统初始化相关 */
$GLOBALS['_beginTime_']=microtime(TRUE);
define('IS_WIN',DIRECTORY_SEPARATOR=='\\'?1:0);
function E($sMessage){
	require_once(DYHB_PATH.'/Resource_/Template/Error.template.php');
	exit();
}
/** 应用路径定义 */
if(!defined('APP_PATH')){
	define('APP_PATH',dirname($_SERVER['SCRIPT_FILENAME']));
}
if(!defined('APP_RUNTIME_PATH')){
	define('APP_RUNTIME_PATH',APP_PATH.'/App/~Runtime');
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   DoYouHaoBaby 基础初始化文件($)*/
/** 系统异常和错误处理 */
set_exception_handler(array('Dyhb','exceptionHandler'));
if(!defined('WINDSFORCE_DEBUG')){
	define('WINDSFORCE_DEBUG',FALSE);
}
if(WINDSFORCE_DEBUG===TRUE){
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
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   异常捕获($)*/
class DException extends Exception{
	private $_sType;
	public function __construct($sMessage,$nCode=0){
		parent::__construct($sMessage,$nCode);
	}
	public function __toString(){
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}
	public function formatException(){
		$arrError=array();
		
		$arrTrace=array_reverse($this->getTrace());
		$this->class=isset($arrTrace['0']['class'])?$arrTrace['0']['class']:'';
		$this->function=isset($arrTrace['0']['function'])?$arrTrace['0']['function']:'';
		
		$sTraceInfo='';
		if($GLOBALS['_commonConfig_']['APP_DEBUG']){
			foreach($arrTrace as $Val){
				$sClass=isset($Val['class'])?$Val['class']:'';
				$this->_sType=$sType=isset($Val['type'])?$Val['type']:'';
				$sFunction=isset($Val['function'])?$Val['function']:'';
				$sFile=isset($Val['file'])?$Val['file']:'';
				$sLine=isset($Val['line'])?$Val['line']:'';
				$args=isset($Val['args'])?$Val['args']:'';
				$sArgsInfo='';
				if(is_array($args)){
					foreach($args as $sK=>$V){
						$sArgsInfo.=($sK!=0?',':'').(is_scalar($V)?strip_tags(var_export($V,true)):gettype($V));
					}
				}
				$sFile=$this->safeFile($sFile);
				$sTraceInfo.="<li>[Line: {$sLine}] {$sFile} - {$sClass}{$sType}{$sFunction}({$sArgsInfo})</li>";
			}
			$arrError['trace']=$sTraceInfo;
		}
		$arrError['message']=$this->message;
		$arrError['type']=$this->_sType;
		$arrError['class']=$this->class;
		$arrError['code']=$this->getCode();
		$arrError['function']=$this->function;
		$arrError['line']=$this->line;
		$arrError['file']=$this->safeFile($this->file);
		return $arrError;
	}
	public function safeFile($sFile){
		$sFile=str_replace(G::tidyPath(DYHB_PATH),'{DYHB_PATH}',G::tidyPath($sFile));
		$sFile=str_replace(G::tidyPath(APP_PATH),'{APP_PATH}',G::tidyPath($sFile));
		if(strpos($sFile,':/') || strpos($sFile,':\\') || strpos($sFile,'/')===0){
			$sFile=basename($sFile);
		}
		return $sFile;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   全局函数集($)*/
class G{
	static function getGpc($sKey,$sVar='R'){
		$sVar=strtoupper($sVar);
		switch($sVar){
			case 'G':$sVar=&$_GET;break;
			case 'P':$sVar=&$_POST;break;
			case 'C':$sVar=&$_COOKIE;break;
			case 'S':$sVar=&$_SESSION;break;
			case 'R':$sVar=&$_REQUEST;break;
			case 'F':$sVar=&$_FILES;break;
		}
		return isset($sVar[$sKey])?$sVar[$sKey]:NULL;
	}
	public static function seccode($arrOption=null,$bChinesecode=false,$nSeccodeTupe=1){
		@header("Expires: -1");// 定义头部
		@header("Cache-Control: no-store,private,post-check=0,pre-check=0,max-age=0",FALSE);
		@header("Pragma: no-cache");
		$nSeccode=G::authcode(Dyhb::cookie('_seccode_'));
		if(!$nSeccode || self::getGpc('update')){
			$nSeccode=G::randString(6,null,true);
			if($bChinesecode and $nSeccodeTupe==1){ // 中文
				$sChineseLang=(string)include(DYHB_PATH.'/Resource_/Images/Seccode/Chinese.inc.php');
				$arrCode=array(substr($nSeccode,0,3),substr($nSeccode,3,3));
				$nSeccode='';
				for($nI=0;$nI<2;$nI++){
					$nSeccode.=substr($sChineseLang,$arrCode[$nI]*3,3);
				}
			}else{
				$sS=sprintf('%04s',base_convert($nSeccode,10,24));
				$sSeccodeUnits='BCEFGHJKMPQRTVWXY2346789';
				$nSeccode='';
				for($nI=0;$nI<4;$nI++){
					$sUnit=ord($sS{$nI});
					$nSeccode.=($sUnit>=0x30 and $sUnit<=0x39)?$sSeccodeUnits[$sUnit-0x30]:$sSeccodeUnits[$sUnit-0x57];
				}
			}
			Dyhb::cookie('_seccode_',G::authcode(strtoupper($nSeccode),FALSE));
		}
		$oCode=new Seccode($arrOption);// 实例化对象
		$oCode->setCode($nSeccode)->display();
	}
	public static function checkSeccode($sSeccode){
		$nOldSeccode=G::authcode(Dyhb::cookie('_seccode_'));
		if(empty($nOldSeccode)){
			return false;
		}
		return $nOldSeccode==strtoupper($sSeccode);// 开始比较数据
	}
	static public function stripslashes($String,$bRecursive=true){
		if($bRecursive===true and is_array($String)){// 递归
			foreach($String as $sKey=>$value){
				$String[self::stripslashes($sKey)]=self::stripslashes($value);// 如果你只注意到值，却没有注意到key
			}
		}else{
			if(is_string($String)){
				$String=stripslashes($String);
			}
		}
		return $String;
	}
	static public function stripslashesMagicquotegpc(){
		if(self::getMagicQuotesGpc()){
			$_GET=self::stripslashes($_GET);
			$_POST=self::stripslashes($_POST);
			$_COOKIE=self::stripslashes($_COOKIE);
			$_REQUEST=self::stripslashes($_REQUEST);
		}
	}
	static public function addslashes($String,$bRecursive=true){
		if($bRecursive===true and is_array($String)){
			foreach($String as $sKey=>$value){
				$String[self::addslashes($sKey)]=self::addslashes($value);// 如果你只注意到值，却没有注意到key
			}
		}else{
			if(is_string($String)){
				$String=addslashes($String);
			}
		}
		return $String;
	}
	static public function getMagicQuotesGpc(){
		return(defined('MAGIC_QUOTES_GPC') && MAGIC_QUOTES_GPC===TRUE);
	}
	static public function varType($Var,$sType){
		$sType=trim($sType);// 整理参数，以支持array:ini格式
		$arrTypes=explode(':',$sType);
		$sRealType=$arrTypes[0];
		$sAllow=isset($arrTypes[1])?$arrTypes[1]:null;
		$sRealType=strtolower($sRealType);
		switch($sRealType){
			case 'string':// 字符串
				return is_string($Var);
			case 'integer':// 整数
			case 'int' :
				return is_int($Var);
			case 'float':// 浮点
				return is_float($Var);
			case 'boolean':// 布尔
			case 'bool':
				return is_bool($Var);
			case 'num':// 数字
			case 'numeric':
				return is_numeric($Var);
			case 'base':// 标量（所有基础类型）
			case 'scalar':
				return is_scalar($Var);
			case 'handle':// 外部资源
			case 'resource':
				return is_resource($Var);
			case 'array':{// 数组
				if($sAllow){
					$arrAllow=explode(',',$sAllow);
					return self::checkArray($Var,$arrAllow);
				}else{
					return is_array($Var);
				}
			}
			case 'object':// 对象
				return is_object($Var);
			case 'null':// 空
			case 'NULL':
				return($Var===null);
			case 'callback':// 回调函数
				return is_callable($Var,false);
			default :// 类
				return self::isKindOf($Var,$sType);
		}
	}
	static public function smartDate($nDateTemp,$sDateFormat='Y-m-d H:i'){
		$sReturn='';
		$nSec=CURRENT_TIMESTAMP-$nDateTemp;
		$nHover=floor($nSec/3600);
		if($nHover==0){
			$nMin=floor($nSec/60);
			if($nMin==0){
				$sReturn=$nSec.' '.Dyhb::L("秒前",'__DYHB__@Dyhb');
			}else{
				$sReturn=$nMin.' '.Dyhb::L("分钟前",'__DYHB__@Dyhb');
			}
		}elseif($nHover<24){
			$sReturn=Dyhb::L("大约 %d 小时前",'__DYHB__@Dyhb',null,$nHover);
		}else{
			$sReturn=date($sDateFormat,$nDateTemp);
		}
		return $sReturn;
	}
	static public function urlGoTo($sUrl,$nTime=0,$sMsg=''){
		$sUrl=str_replace(array("\n","\r"),'',$sUrl);// 多行URL地址支持
		if(empty($sMsg)){
			$sMsg=Dyhb::L("系统将在%d秒之后自动跳转到%s。",'__DYHB__@Dyhb',null,$nTime,$sUrl);
		}
		if(!headers_sent()){
			if(0==$nTime){
				header("Location:".$sUrl);
			}else{
				header("refresh:{$nTime};url={$sUrl}");
				echo($sMsg);
			}
			exit();
		}else{
			$sStr="<meta http-equiv='Refresh' content='{$nTime};URL={$sUrl}'>";
			if($nTime!=0){
				$sStr.=$sMsg;
			}
			exit($sStr);
		}
	}
	static public function randString($nLength,$sCharBox=null,$bNumeric=false){
		if($bNumeric===true){
			return sprintf('%0'.$nLength.'d',mt_rand(1,pow(10,$nLength)-1));
		}
		if($sCharBox===null){
			$sBox=strtoupper(md5(self::now(true).rand(1000000000,9999999999)));
			$sBox.=md5(self::now(true).rand(1000000000,9999999999));
		}else{
			$sBox=&$sCharBox;
		}
		$nN=$nLength;
		$nBoxEnd=strlen($sBox)-1;
		$sRet='';
		while($nN--){
			$sRet.=substr($sBox,rand(0,$nBoxEnd),1);
		}
		return $sRet;
	}
	static public function now($bExact=true){
		if($bExact){
			list($nMS,$nS)=explode(' ',microtime());
			return $nS+$nMS;
		}else{
			return CURRENT_TIMESTAMP;
		}
	}
	static public function gbkToUtf8($FContents,$sFromChar,$sToChar='utf-8'){
		if(empty($FContents)){
			return $FContents;
		}
		$sFromChar=strtolower($sFromChar)=='utf8'?'utf-8':strtolower($sFromChar);
		$sToChar=strtolower($sToChar)=='utf8'?'utf-8':strtolower($sToChar);
		if($sFromChar==$sToChar || (is_scalar($FContents) && !is_string($FContents))){
			return $FContents;
		}
		if(is_string($FContents)){
			if(function_exists('mb_convert_encoding')){
				return mb_convert_encoding($FContents,$sFromChar,$sToChar);
			}elseif(function_exists('iconv')){
				return iconv($FContents,$sFromChar,$sToChar);
			}else{
				return $FContents;
			}
		}elseif(is_array($FContents)){
			foreach($FContents as $sKey=>$sVal){
				$sKeyTwo=self::gbkToUtf8($sKey,$sFromChar,$sToChar);
				$FContents[$sKeyTwo]=self::gbkToUtf8($sVal,$sFromChar,$sToChar);
				if($sKey!=$sKeyTwo){
					unset($FContents[$sKeyTwo]);
				}
			}
			return $FContents;
		}else{
			return $FContents;
		}
	}
	public static function isUtf8($sString){
		$nLength=strlen($sString);
		for($nI=0;$nI<$nLength;$nI++){
			if(ord($sString[$nI])<0x80){
				$nN=0;
			}elseif((ord($sString[$nI])&0xE0)==0xC0){
				$nN=1;
			}elseif((ord($sString[$nI])&0xF0)==0xE0){
				$nN=2;
			}elseif((ord($sString[$nI])&0xF0)==0xF0){
				$nN=3;
			}else{
				return FALSE;
			}
			for($nJ=0;$nJ<$nN;$nJ++){
				if((++$nI==$nLength) ||((ord($sString[$nI])&0xC0)!=0x80)){
					return FALSE;
				}
			}
		}
		return TRUE;
	}
	static public function subString($sStr,$nStart=0,$nLength=255,$sCharset="utf-8",$bSuffix=true){
		// 对系统的字符串函数进行判断
		if(function_exists("mb_substr")){
			return mb_substr($sStr,$nStart,$nLength,$sCharset);
		}elseif(function_exists('iconv_substr')){
			return iconv_substr($sStr,$nStart,$nLength,$sCharset);
		}
		// 常用几种字符串正则表达式
		$arrRe['utf-8']="/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$arrRe['gb2312']="/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$arrRe['gbk']="/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$arrRe['big5']="/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		
		// 匹配
		preg_match_all($arrRe[$sCharset],$sStr,$arrMatch);
		$sSlice=join("",array_slice($arrMatch[0],$nStart,$nLength));
		if($bSuffix){
			return $sSlice."…";
		}
		return $sSlice;
	}
	static public function isSameCallback($CallbackA,$CallbackB){
		if(!is_callable($CallbackA) || is_callable($CallbackB)){
			return false;
		}
		if(is_array($CallbackA)){
			if(is_array($CallbackB)){
				return($CallbackA[0]===$CallbackB[0]) AND (strtolower($CallbackA[1])===strtolower($CallbackB[1]));
			}else{
				return false;
			}
		}else{
			return strtolower($CallbackA)===strtolower($CallbackB);
		}
	}
	static public function isThese($Var,$Types){
		if(!self::varType($Types,'string') && !self::checkArray($Types,array('string'))){
			Dyhb::E(Dyhb::L('正确格式:参数 $Types 必须为 string 或 各项元素为string的数组','__DYHB__@Dyhb'));
		}
		if(is_string($Types)){
			$arrTypes=array($Types);
		}else{
			$arrTypes=$Types;
		}
		foreach($arrTypes as $sType){// 类型检查
			if(self::varType($Var,$sType)){
				return true;
			}
		}
		return false;
	}
	static public function isKindOf($SubClass,$sBaseClass){
		if(Dyhb::classExists($sBaseClass,true)){// 接口
			return self::isImplementedTo($SubClass,$sBaseClass);
		}else{// 类
			if(is_object($SubClass)){// 统一类名,如果不是，返回false
				$sSubClassName=get_class($SubClass);
			}elseif(is_string($SubClass)){
				$sSubClassName=&$SubClass;
			}else{
				return false;
			}
			if($sSubClassName==$sBaseClass){// 子类名 即为父类名
				return true;
			}
			$sParClass=get_parent_class($sSubClassName);// 递归检查
			if(!$sParClass){
				return false;
			}
			return self::isKindOf($sParClass,$sBaseClass);
		}
	}
	static public function isImplementedTo($Class,$sInterface,$bStrictly=false){
		if(is_object($Class)){// 尝试获取类名，否则返回false
			$sClassName=get_class($Class);
		}elseif(is_string($Class)){
			$sClassName=&$Class;
		}else{
			return false;
		}
		if(!is_string($sClassName)){// 类型检查
			return false;
		}
		if(!class_exists($sClassName) || !interface_exists($sInterface)){// 检查类和接口是否都有效
			return false;
		}
		// 建立反射
		$oReflectionClass=new ReflectionClass($sClassName);
		$arrInterfaceRefs=$oReflectionClass->getInterfaces();
		foreach($arrInterfaceRefs as $oInterfaceRef){
			if($oInterfaceRef->getName()!=$sInterface){
				continue;
			}
			if(!$bStrictly){// 找到 匹配的 接口
				return true;
			}
			// 依次检查接口中的每个方法是否实现
			$arrInterfaceFuncs=get_class_methods($sInterface);
			foreach($arrInterfaceFuncs as $sFuncName){
				$sReflectionMethod=$oReflectionClass->getMethod($sFuncName);
				if($sReflectionMethod->isAbstract()){// 发现尚为抽象的方法
					return false;
				}
			}
			return true;
		}
		// 递归检查父类
		if(($sParName=get_parent_class($sClassName))!==false){
			return self::isImplementedTo($sParName,$sInterface,$bStrictly);
		}else{
			return false;
		}
	}
	static public function checkArray($arrArray,array $arrTypes){
		if(!is_array($arrArray)){// 不是数组直接返回
			return false;
		}
		// 判断数组内部每一个值是否为给定的类型
		foreach($arrArray as &$Element){
			$bRet=false;
			foreach($arrTypes as $Type){
				if(self::varType($Element,$Type)){
					$bRet=true;
					break;
				}
			}
			if(!$bRet){
				return false;
			}
		}
		return true;
	}
	static public function tidyPath($sPath,$bUnix=true){
		$sRetPath=str_replace('\\','/',$sPath);// 统一 斜线方向
		$sRetPath=preg_replace('|/+|','/',$sRetPath);// 归并连续斜线
		$arrDirs=explode('/',$sRetPath);// 削除 .. 和  .
		$arrDirs2=array();
		while(($sDirName=array_shift($arrDirs))!==null){
			if($sDirName=='.'){
				continue;
			}
			if($sDirName=='..'){
				if(count($arrDirs2)){
					array_pop($arrDirs2);
					continue;
				}
			}
			array_push($arrDirs2,$sDirName);
		}
		$sRetPath=implode('/',$arrDirs2);// 目录 以  '/' 结尾
		if(@is_dir($sRetPath)){// 存在的目录
			if(!preg_match('|/$|',$sRetPath)){
				$sRetPath.= '/';
			}
		}else if(preg_match("|\.$|",$sPath)){// 不存在，但是符合目录的格式
			if(!preg_match('|/$|',$sRetPath)){
				$sRetPath.= '/';
			}
		}
		$sRetPath=str_replace(':/',':\\',$sRetPath);// 还原 驱动器符号
		if(!$bUnix){// 转换到 Windows 斜线风格
			$sRetPath=str_replace('/','\\',$sRetPath);
		}
		$sRetPath=rtrim($sRetPath,'\\/');// 删除结尾的“/”或者“\”
		return $sRetPath;
	}
	static public function dump($Var,$bEcho=true,$sLabel=null,$bStrict=true){
		$SLabel=($sLabel===null)?'':rtrim($sLabel).' ';
		if(!$bStrict){
			if(ini_get('html_errors')){
				$sOutput=print_r($Var,true);
				$sOutput="<pre>".$sLabel.htmlspecialchars($sOutput,ENT_QUOTES)."</pre>";
			}else{
				$sOutput=$sLabel." : ".print_r($Var,true);
			}
		}else{
			ob_start();
			var_dump($Var);
			$sOutput=ob_get_clean();
			if(!extension_loaded('xdebug')){
				$sOutput=preg_replace("/\]\=\>\n(\s+)/m","] => ",$sOutput);
				$sOutput='<pre>'.$sLabel.htmlspecialchars($sOutput,ENT_QUOTES).'</pre>';
			}
		}
		if($bEcho){
			echo $sOutput;
			return null;
		}else{
			return $sOutput;
		}
	}
	static public function makeDir($Dir,$nMode=0777){
		if(is_dir($Dir)){
			return true;
		}
		if(is_string($Dir)){
			$arrDirs=explode('/',str_replace('\\','/',trim($Dir,'/')));
		}else{
			$arrDirs=$Dir;
		}
		$sMakeDir=IS_WIN?'':'/';
		foreach($arrDirs as $nKey=>$sDir){
			$sMakeDir.=$sDir.'/';
			if(!is_dir($sMakeDir)){
				if(isset($arrDirs[$nKey+1]) && is_dir($sMakeDir.$arrDirs[$nKey+1])){
					continue;
				}
				@mkdir($sMakeDir,$nMode);
			}
		}
		return TRUE;
	}
	static public function getRelativePath($sFromPath,$sToPath){
		if(@is_file($sFromPath)){// 如果 $sFromPath 是一个文件，取其目录部分
			$sFrom=dirname($sFromPath);
		}else{
			$sFrom=&$sFromPath;
		}
		if(IS_WIN){
			$sFrom=strtolower($sFrom);
			$sToPath=strtolower($sToPath);
		}
		$sFrom=self::tidyPath($sFrom);// 整理路径为统一格式
		$sTo=self::tidyPath($sToPath);
		$arrFromPath=explode('/',$sFrom);// 切为数组
		$arrToPath=explode('/',$sTo);
		array_diff($arrFromPath,array(''));// 排除 空元素
		array_diff($arrToPath,array(''));
		$nSameLevel=0;// 开始比较
		while(
			($sFromOneDir=array_shift($arrFromPath))!==null
			and ($sToOneDir=array_shift($arrToPath))!==null
			and ($sFromOneDir===$sToOneDir)
		)
		{
			$nSameLevel++;
		}
		if($sFromOneDir!==null){// 将 相同的 目录 压回 栈中
			array_unshift($arrFromPath,$sFromOneDir);
		}
		if($sToOneDir!==null){
			array_unshift($arrToPath,$sToOneDir);
		}
		if($nSameLevel<=0){// 不在 同一 磁盘驱动器 中(Windows 环境下)
			return null;
		}
		$nLevel=count($arrFromPath)-1;// 返回
		$sRelativePath=($nLevel>0)?str_repeat('../',$nLevel):'';
		$sRelativePath.=implode('/',$arrToPath);
		$sRelativePath=rtrim($sRelativePath,'/');
		return $sRelativePath;
	}
	static public function changeFileSize($nFileSize){
		if($nFileSize>=1073741824){
			$nFileSize=round($nFileSize/1073741824,2).'GB';
		}elseif($nFileSize>=1048576){
			$nFileSize=round($nFileSize/1048576,2).'MB';
		}elseif($nFileSize>=1024){
			$nFileSize=round($nFileSize/1024,2).'KB';
		}else{
			$nFileSize=$nFileSize.Dyhb::L('字节','__DYHB__@dyhb');
		}
		return $nFileSize;
	}
	static public function getMicrotime(){
		list($nM1,$nM2)=explode(' ',microtime());
		return((float)$nM1+(float)$nM2);
	}
	static public function oneImensionArray($arrArray){
		return count($arrArray)==count($arrArray,1);
	}
	static public function getIp(){
		static $sRealip=NULL;
		if($sRealip !== NULL){
			return $sRealip;
		}
		if(isset($_SERVER)){
			if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
				$arrValue=explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
					foreach($arrValue AS $sIp){// 取X-Forwarded-For中第一个非unknown的有效IP字符串
						$sIp=trim($sIp);
						if($sIp!='unknown'){
							$sRealip=$sIp;
							break;
						}
					}
				}elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
					$sRealip=$_SERVER['HTTP_CLIENT_IP'];
				}else{
					if(isset($_SERVER['REMOTE_ADDR'])){
						$sRealip=$_SERVER['REMOTE_ADDR'];
					}else{
						$sRealip='0.0.0.0';
					}
				}
			}else{
				if(getenv('HTTP_X_FORWARDED_FOR')){
					$sRealip=getenv('HTTP_X_FORWARDED_FOR');
				}elseif(getenv('HTTP_CLIENT_IP')){
					$sRealip=getenv('HTTP_CLIENT_IP');
				}else{
					$sRealip=getenv('REMOTE_ADDR');
				}
			}
			preg_match("/[\d\.]{7,15}/",$sRealip,$arrOnlineip);
			$sRealip=!empty($arrOnlineip[0])?$arrOnlineip[0]:'0.0.0.0';
			return $sRealip;
	}
	static public function authcode($string,$operation=TRUE,$key=null,$expiry=3600){
		$ckey_length=4;
		$key=md5($key?$key:$GLOBALS['_commonConfig_']['DYHB_AUTH_KEY']);
		$keya=md5(substr($key,0,16));
		$keyb=md5(substr($key,16,16));
		$keyc=$ckey_length?($operation===TRUE?substr($string,0,$ckey_length):substr(md5(microtime()),-$ckey_length)):'';
		$cryptkey=$keya.md5($keya.$keyc);
		$key_length=strlen($cryptkey);
		$string=$operation===TRUE?base64_decode(substr($string, $ckey_length)):sprintf('%010d',$expiry?$expiry+time():0).substr(md5($string.$keyb),0,16).$string;
		$string_length=strlen($string);
		$result='';
		$box=range(0,255);
		$rndkey=array();
		for($i=0;$i<=255;$i++){
			$rndkey[$i]=ord($cryptkey[$i%$key_length]);
		}
		for($j=$i=0;$i<256;$i++){
			$j=($j+$box[$i]+$rndkey[$i])%256;
			$tmp=$box[$i];
			$box[$i]=$box[$j];
			$box[$j]=$tmp;
		}
		for($a=$j=$i=0;$i<$string_length;$i++){
			$a=($a+1)%256;
			$j=($j+$box[$a])%256;
			$tmp=$box[$a];
			$box[$a]=$box[$j];
			$box[$j]=$tmp;
			$result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
		}
		if($operation===TRUE){
			if((substr($result,0,10)==0 || substr($result,0,10)-time()>0) && substr($result,10,16)==substr(md5(substr($result,26).$keyb),0,16)){
				return substr($result,26);
			}else{
				return '';
			}
		}else{
			return $keyc.str_replace('=','',base64_encode($result));
		}
	}
	static public function returnBytes($sVal){
		$sVal=trim($sVal);
		$sLast=strtolower($sVal{strlen($sVal)-1});
		switch($sLast){
			case 'g':
				$sVal*=1024*1024*1024;
			case 'm':
				$sVal*=1024*1024;
			case 'k':
				$sVal*=1024;
		}
		return $sVal;
	}
	static public function listDir($sDir,$bFullPath=FALSE,$bReturnFile=FALSE){
		if(is_dir($sDir)){
			$arrFiles=array();
			$hDir=opendir($sDir);
			while(($sFile=readdir($hDir))!==FALSE){
				if($bReturnFile===FALSE){
					if((is_dir($sDir."/".$sFile)) && $sFile!="." && $sFile!=".." && $sFile!='_svn'){
						if($bFullPath===TRUE){
							$arrFiles[]=$sDir."/".$sFile;
						}else{
							$arrFiles[]=$sFile;
						}
					}
				}else{
					if((is_file($sDir."/".$sFile)) && $sFile!="." && $sFile!=".."){
						if($bFullPath===TRUE){
							$arrFiles[]=$sDir."/".$sFile;
						}else{
							$arrFiles[]=$sFile;
						}
					}
				}
			}
			closedir($hDir);
			return $arrFiles;
		}else{
			return false;
		}
	}
	public static function hasStaticMethod($sClassName,$sMethodName){
		$oRef=new ReflectionClass($sClassName);
		if($oRef->hasMethod($sMethodName) and $oRef->getMethod($sMethodName)->isStatic()){
			return true;
		}
		return false;
	}
	static public function mbUnserialize($sSerial){
		$sSerial=preg_replace('!s:(\d+):"(.*?)";!se',"'s:'.strlen('$2').':\"$2\";'",$sSerial);
		$sSerial=str_replace("\r","",$sSerial);
		return unserialize($sSerial);
	}
	static public function getAvatar($nUid,$sSize='middle'){
		$sSize=in_array($sSize,array('big','middle','small','origin'))?$sSize:'middle';
		$nUid=abs(intval($nUid));
		$nUid=sprintf("%09d",$nUid);
		$nDir1=substr($nUid,0,3);
		$nDir2=substr($nUid,3,2);
		$nDir3=substr($nUid,5,2);
		return $nDir1.'/'.$nDir2.'/'.$nDir3.'/'.substr($nUid,-2)."_avatar_{$sSize}.jpg";
	}
	static public function getExtName($sFileName,$nCase=0){
		if(!preg_match('/\./',$sFileName)){
			return '';
		}
		$arr=explode('.',$sFileName);
		$sExtName=end($arr);
		if($nCase==1){
			return strtoupper($sExtName);
		}elseif($nCase==2){
			return strtolower($sExtName);
		}else{
			return $sExtName;
		}
	}
	static public function cleanJs($sText){
		$sText=trim($sText);
		$sText=stripslashes($sText);
		$sText=preg_replace('/<!--?.*-->/','',$sText);// 完全过滤注释
		$sText=preg_replace('/<\?|\?>/','',$sText);// 完全过滤动态代码
		$sText=preg_replace('/<script?.*\/script>/','',$sText);// 完全过滤js
		$sText=preg_replace('/<\/?(html|head|meta|link|base|body|title|style|script|form|iframe|frame|frameset)[^><]*>/i','',$sText);// 过滤多余html
		while(preg_match('/(<[^><]+)(lang|onfinish|onmouse|onexit|onerror|onclick|onkey|onload|onchange|onfocus|onblur)[^><]+/i',$sText,$arrMat)){//过滤on事件lang js
			$sText=str_replace($arrMat[0],$arrMat[1],$sText);
		}
		while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$sText,$arrMat)){
			$sText=str_replace($arrMat[0],$arrMat[1].$arrMat[3],$sText);
		}
		return $sText;
	}
	static function text($sText){
		$sText=self::cleanJs($sText);
		$sText=preg_replace('/\s(?=\s)/','',$sText);// 彻底过滤空格
		$sText=preg_replace('/[\n\r\t]/',' ',$sText);
		$sText=str_replace('  ',' ',$sText);
		$sText=str_replace(' ','',$sText);
		$sText=str_replace('&nbsp;','',$sText);
		$sText=str_replace('&','',$sText);
		$sText=str_replace('=','',$sText);
		$sText=str_replace('-','',$sText);
		$sText=str_replace('#','',$sText);
		$sText=str_replace('%','',$sText);
		$sText=str_replace('!','',$sText);
		$sText=str_replace('@','',$sText);
		$sText=str_replace('^','',$sText);
		$sText=str_replace('*','',$sText);
		$sText=str_replace('amp;','',$sText);
		$sText=strip_tags($sText);
		$sText=htmlspecialchars($sText);
		$sText=str_replace("'","",$sText);
		return $sText;
	}
	static public function html($sText){
		$sText=trim($sText);
		$sText=htmlspecialchars($sText);
		return $sText;
	}
	static public function htmlView($sText){
		$sText=stripslashes($sText);
		$sText=nl2br($sText);
		return $sText;
	}
	static public function xmlEncode($arrData=array()){
		return Xml::xmlSerialize($arrData);
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   初始化基本配置($)*/
if(!is_file(APP_RUNTIME_PATH.'/Config.php')){
	require(DYHB_PATH.'/Common_/AppConfig.inc.php');
}
$GLOBALS['_commonConfig_']=Dyhb::C((array)(include APP_RUNTIME_PATH.'/Config.php'));
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   语言包管理类($)*/
class LangPackage{
	private $_sPackageName='';
	private $_sLangName='';
	private $_sPackagePath='';
	public $LANGS=array();
	private $_bNeedUpdated=false;
	private $_nUpdateTime=0;
	static private $LANG_PACKAGES=array();
	static private $_sHistoryPath=null;
	static public function getPackage($sLangName,$sPackageName){
		if(isset(self::$LANG_PACKAGES[$sLangName][$sPackageName])){// 直接返回已经被创建的享员对象
			return self::$LANG_PACKAGES[$sLangName][$sPackageName];
		}else{// 创建对象
			$oThePackage=null;
			
			// 分析语言包路径
			if(strpos($sPackageName,'@')!==false){
				$arrValues=explode('@',$sPackageName);
				if($arrValues[0]==='__DYHB__'){
					$sDir=DYHB_PATH.'/Resource_/Lang';
				}elseif($arrValues[0]==='__APP__'){
					$sDir=APP_LANG_PATH;
				}else{
					$bDefined=false;
					eval('$bDefined=defined(\''.$arrValues[0].'\');');
					if($bDefined){
						eval('$sDir='.$arrValues[0].';');
					}else{
						$sDir=$arrValues[0];
					}
				}
				$sPackageName=$arrValues[1];
			}else{
				$sDir=APP_LANG_PATH;
			}
			$sThePackagePath=self::findPackage($sDir,$sLangName,$sPackageName);
			
			if($oThePackage){
				$oThePackage->loadPackageFile($sThePackagePath);
			}else{
				$oThePackage=self::createPackage($sLangName,$sPackageName,$sThePackagePath);
			}
			if($oThePackage===null){
				E('We can not find a lang file:<br/>'.self::$_sHistoryPath);
				exit();
			}
			return $oThePackage;
		}
	}
	static private function findPackage($sDir,$sLangName,$sPackageName=null){
		$sDir=ucfirst($sDir);
		$sLangName=ucfirst($sLangName);
		$sPackageName=ucfirst($sPackageName);
		$sPath="{$sDir}/{$sLangName}/{$sPackageName}.lang.php";
		if(is_file($sPath)){
			return $sPath;
		}elseif(is_file("{$sDir}/Zh-cn/{$sPackageName}.lang.php")){// 尝试从默认语言环境中加载
			return "{$sDir}/Zh-cn/{$sPackageName}.lang.php";
		}
		self::$_sHistoryPath=$sPath;
		return null;
	}
	public function loadPackageFile($sPackagePath){
		if(!is_file($sPackagePath)){
			E(sprintf('PackagePath [ %s ] is not exists',self::$_sHistoryPath));
		}
		$this->LANGS=array_merge($this->LANGS,(array)(include $sPackagePath));
		if($this->_nUpdateTime<filemtime($sPackagePath)){// 更新语言包的时间
			$this->_nUpdateTime=filemtime($sPackagePath);
		}
	}
	static private function createPackage($sLangName,$sPackageName,$sPackagePath){
		if(isset(self::$LANG_PACKAGES[$sLangName][$sPackageName])){
			return self::$LANG_PACKAGES[$sLangName][$sPackageName];
		}
		return self::$LANG_PACKAGES[$sLangName][$sPackageName]=new self($sLangName,$sPackageName,$sPackagePath);
	}
	private function __construct($sLangName,$sPackageName,$sPackagePath){
		$this->_sPackagePath=$sPackagePath;
		$this->_sPackageName=$sPackageName;
		$this->_sLangName=$sLangName;
		$this->load();
	}
	public function __destruct(){
		if($this->isUpdated()){
			$this->save();
		}
	}
	public function load(){
		$this->LANGS=array();
		$this->loadPackageFile($this->_sPackagePath);
	}
	public function save(){
		$sOut="<?php\r\n";
		$sOut.="/** DoYouHaoBaby Framework Lang File, Do not to modify it! */\r\n";
		$sOut.="return array(\r\n";
		foreach($this->LANGS as $sKey=>$sValue){
			$sValue=$this->filterOptionValue($sValue);
			$sOut.="'{$sKey}'=>$sValue,\r\n";
		}
		$sOut.=")\r\n";
		$sOut.="\r\n?>";
		if(!file_put_contents($this->_sPackagePath,$sOut)){
			E('Configuration write failed system language,if your sever is Linux hosts ,set permissions to 0777 ,the path is'.$this->_sPackagePath);
		}
	}
	private function filterOptionValue($sValue){
		if($sValue===false){
			return 'FALSE';
		}
		if($sValue===true){
			return 'TRUE';
		}
		if($sValue==''){
			return '""';
		}
		$sValue=str_replace('"','\\"',$sValue);
		$sValue=str_replace("\n","\\n",$sValue);
		$sValue=str_replace("\r","\\r",$sValue);
		$sValue=str_replace('$','\\$',$sValue);
		return '"'.$sValue.'"';
	}
	public function getName(){
		return $this->_sPackageName;
	}
	public function getLangName(){
		return $this->_sLanguageName;
	}
	public function set($sKey,$sValue){
		if($this->get($sKey)==$sValue){
			return;
		}
		$this->LANGS[$sKey]=$sValue;
		$this->_bNeedUpdated=true;
	}
	public function get($sKey){
		if($this->has($sKey)){
			return $this->LANGS[$sKey];
		}else{
			return null;
		}
	}
	public function has($sKey){
		return isset($this->LANGS[$sKey]);
	}
	public function isUpdated(){
		return $this->_bNeedUpdated;
	}
	public function getUpdateTime(){
		return $this->_nUpdateTime;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   语言管理类($)*/
class Lang{
	const CURRENT_LANGUAGE=null;
	private $_sLangName;
	private $_oCurrentPackage=null;
	static private $LANG_INSES;
	static private $_oCurrentLang;
	private function __construct($sLangName){
		$this->_sLangName=$sLangName;
	}
	static public function getLang($sLangName){
		if(isset(self::$LANG_INSES[$sLangName])){
			return self::$LANG_INSES[$sLangName];
		}
		$oLang=new Lang($sLangName);
		self::$LANG_INSES[$sLangName]=$oLang;
		if(!self::$_oCurrentLang){// 若无当前语言实例,自动设置为当前语言实例
			self::$_oCurrentLang=$oLang;
		}
		return $oLang;
	}
	static public function setCurrentLang($Lang){
		$oOldValue=self::getCurrentLang();
		if(is_string($Lang)){
			self::$_oCurrentLang=self::getLang($Lang);
		}elseif($Lang instanceof Lang){
			self::$_oCurrentLang=$Lang;
		}else{
			E('Parameters $Lang must be a name(String type), the language object ever have be created,or null(the current language pack)');
		}
		return $oOldValue;
	}
	static public function getCurrentLang(){
		return self::$_oCurrentLang;
	}
	public function getLangName(){
		return $this->_sLangName;
	}
	public function getPackage($sPackageName){
		$oThePackage=LangPackage::getPackage($this->getLangName(),$sPackageName);
		if(!$oThePackage){
			E('Can not find the language pack according to the parameters \$sPackageName({$sPackageName}.');
		}
		if(!$this->getCurrentPackage()){
			$this->setCurrentPackage($oThePackage);
		}
		return $oThePackage;
	}
	public function getCurrentPackage(){
		return $this->_oCurrentPackage;
	}
	public function setCurrentPackage($Package){
		$oOldValue=$this->getCurrentPackage();
		if(is_string($Package)){
			$this->_oCurrentPackage=$this->getPackage($Package);
		}elseif($Package instanceof LangPackage){
			$this->_oCurrentPackage=$Package;
		}else{
			E('Parameters $Packaqe must be a language  name(String type), the language pack object ever have be created,or null(the current language pack)');
		}
		
		return $oOldValue;
	}
	static public function makeValueKey($sValue){
		return md5($sValue);
	}
	static public function setEx($sValue,$Package=null,$Lang=null/*Argvs*/){
		$sKey=self::makeValueKey($sValue);
		// 取得语言享员对象
		if(is_string($Lang)){
			$oTheLang=self::getLang($Lang);
		}elseif(is_object($Lang)){
			$oTheLang=$Lang;
		}elseif($Lang===null){
			$oTheLang=self::getCurrentLang();
			if(!$oTheLang){
				E('Not specify the current language ,triggering an exception!');
			}
		}
		
		// 取得语言包
		if(is_string($Package)){
			$oThePackage=$oTheLang->getPackage($Package);
		}elseif(is_object($Package)){
			$oThePackage=$Package;
		}elseif($Package===null){
			$oThePackage=$oTheLang->getCurrentPackage();
			if(!$oThePackage){
				E('Not specify the current language ,triggering anexception !');
			}
		}
		// 语句存在
		if($oThePackage->has($sKey)){
			$sReallyValue=$oThePackage->get($sKey);
		}else{
			$sReallyValue=$sValue;
			$oThePackage->set($sKey,$sReallyValue);
		}
		if(func_num_args()>3){// 代入参数
			$arrArgs=func_get_args();
			$arrArgs[0]=$sReallyValue;
			unset($arrArgs[1],$arrArgs[2]);
			$sReallyValue=call_user_func_array('sprintf',$arrArgs);
		}
		return $sReallyValue;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   语言包($)*/
class LangPackageForClient{
	static public function getLangPackage($sLangName,$sPackageName){
		$oThePackage=LangPackage::getPackage($sLangName,$sPackageName);
		if(!$oThePackage){
			return;
		}
		header('Content-type: text/html; charset=utf-8');
		// 向客户端传送语言包内容
		echo '{';
		$nLine=0;
		foreach($oThePackage->LANGS as $key=>$sLang){
			if($nLine++){
				echo ',';
			}
			echo '"'.$key.'"'.':'.'"'.$sLang.'"';
		}
		echo '}';
	}
	static public function setNewSentence($sSentenceKey,$sSentence,$sLangName,$sPackageName){
		$thePackage=LangPackage::getPackage($sLangName,$sPackageName);
		if(!$thePackage){
			echo(sprintf('can not find lang:%s,package:%s.','LibDyhb',$sLangName,$sPackageName));
			return;
		}
		$thePackage->set($sSentenceKey,$sSentence);
		print '1';
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   全局控制器($)*/
class App{
	private static $_oControl;
	private static $_bEmptyModel=false;
	static private function init_(){
		// 初始化
		// header('DoYouHaoBaby-Framework | '.DYHB_VERSION); - Apache Internal Server Error 500
		session_start();
		// 移除自动转义
		G::stripslashesMagicquotegpc();
		// 载入DoYouHaoBaby框架
		Dyhb::import(DYHB_PATH);
		//if(!is_file(DYHB_PATH.'/Resource_/Js/Dyhb.package.js')){
			//Dyhb::importJsPackage('Dyhb',false);
		//}
		// 初始化时区和GZIP压缩
		if(function_exists('date_default_timezone_set')){
			date_default_timezone_set($GLOBALS['_commonConfig_']['TIME_ZONE']);
		}
		if($GLOBALS['_commonConfig_']['START_GZIP'] && function_exists('gz_handler')){
			ob_start('gz_handler');
		}
		// 解析系统URL
		$oUrl=new Url();
		$oUrl->parseUrl();
		// 检查语言包和模板以及定义系统常量
		self::checkTemplate();
		self::checkLanguage();
 		self::constantDefine();
		
		// 载入项目初始化文件
		require(APP_PATH.'/App/DoYouHaoBaby.php');
		// 开启静态缓存
		if($GLOBALS['_commonConfig_']['HTML_CACHE_ON']){
			Html::R();
		}
		// 载入应用
		Dyhb::import(APP_PATH.'/App/Class');
		return;
	}
	static public function RUN(){
		self::init_();
		self::execute();
		if($GLOBALS['_commonConfig_']['LOG_RECORD']){
			Log::S();
		}
		return;
	}
	static public function execute(){
		// 读取模块资源
		$sModule=ucfirst(MODULE_NAME)."Controller";
		if(Dyhb::classExists($sModule,false,true)){
			$oModule=new $sModule();
		}elseif(isset($GLOBALS['_commonConfig_'][strtoupper('_M_'.MODULE_NAME)])){
			$sModule=ucfirst(strtolower($GLOBALS['_commonConfig_'][strtoupper('_M_'.MODULE_NAME)]))."Controller";
			if(!class_exists($sModule,false)){
				Dyhb::E(Dyhb::L('%s 的扩展模块%s 不存在','__DYHB__@Dyhb',null,MODULE_NAME,$sModule));
			}
			$oModule=new $sModule();
		}else{
			$oModule=self::emptyModule();
		}
		if($oModule===false){
			if($GLOBALS['_commonConfig_']['NOT_ALLOWED_EMPTYCONTROL_VIEW']===true){
				Dyhb::E(Dyhb::L('模块%s 不存在','__DYHB__@Dyhb',null,$sModule));
			}else{
				$bResult=self::display();
			}
		}
		self::$_oControl=$oModule;
		// 执行控制器公用初始化函数
		if(method_exists($oModule,'init__')){
			call_user_func(array($oModule,'init__'));
		}
		// 执行控制器方法
		if(method_exists($oModule,'b'.ucfirst(ACTION_NAME).'_')){
			call_user_func(array($oModule,'b'.ucfirst(ACTION_NAME).'_'));
		}
		if(method_exists($oModule,ACTION_NAME)){
			call_user_func(array($oModule,ACTION_NAME));
			$bResult=true;
		}else{
			$bResult=self::emptyAction($oModule);
		}
		if($bResult===false){
			if($GLOBALS['_commonConfig_']['NOT_ALLOWED_EMPTYACTION_VIEW']===true){
				Dyhb::E(Dyhb::L('模块%s 不存在的方法%s 不存在','__DYHB__@Dyhb',null,$sModule,ACTION_NAME));
			}else{
				$bResult=self::display();
			}
		}
		if(method_exists($oModule,'a'.ucfirst(ACTION_NAME).'_')){
			call_user_func(array($oModule,'a'.ucwords(ACTION_NAME).'_'));
		}
	}
	private static function emptyModule(){
		self::$_bEmptyModel=true;
		$sModule=ucfirst(strtolower($GLOBALS['_commonConfig_']['EMPTY_MODULE_NAME']))."Controller";
		if(!Dyhb::classExists($sModule,false,true)){
			return false;
		}
		return new $sModule();
	}
	private static function emptyAction($oModule){
		if(method_exists($oModule,$GLOBALS['_commonConfig_']['EMPTY_ACTION_NAME'])){
			call_user_func(array($oModule,$GLOBALS['_commonConfig_']['EMPTY_ACTION_NAME']));
		}else{
			return false;
		}
	}
	static private function display(){
		$oController=new Controller();
		return $oController->display();
	}
	static private function checkTemplate(){
		if(!defined('APP_TEMPLATE_PATH')){
			define('APP_TEMPLATE_PATH',APP_PATH.'/Theme');
		}
		if($GLOBALS['_commonConfig_']['COOKIE_LANG_TEMPLATE_INCLUDE_APPNAME']===TRUE){
			$sCookieName=APP_NAME.'_template';
		}else{
			$sCookieName='template';
		}
		if(!$GLOBALS['_commonConfig_']['THEME_SWITCH']){
			$sTemplateSet=ucfirst(strtolower($GLOBALS['_commonConfig_']['TPL_DIR']));
		}elseif(isset($_GET['t'])){
			$sTemplateSet=ucfirst(strtolower($_GET['t']));
		}else{
			if(Dyhb::cookie($sCookieName)){
				$sTemplateSet=Dyhb::cookie($sCookieName);
			}else{
				$sTemplateSet=ucfirst(strtolower($GLOBALS['_commonConfig_']['TPL_DIR']));
			}
		}
		Dyhb::cookie($sCookieName,$sTemplateSet);
		define('TEMPLATE_NAME',$sTemplateSet);
		define('TEMPLATE_PATH',APP_TEMPLATE_PATH.'/'.TEMPLATE_NAME);
		define('TEMPLATE_PATH_DEFAULT',APP_TEMPLATE_PATH.'/Default');
		if(!is_dir(TEMPLATE_PATH)){
			$sTemplatePath=APP_TEMPLATE_PATH.'/Default';
		}else{
			$sTemplatePath=TEMPLATE_PATH;
		}
		Template::setTemplateDir($sTemplatePath);
		return;
	}
	static private function checkLanguage(){
		if(!defined('APP_LANG_PATH')){
			define('APP_LANG_PATH',APP_PATH.'/App/Lang');
		}
		if($GLOBALS['_commonConfig_']['COOKIE_LANG_TEMPLATE_INCLUDE_APPNAME']===TRUE){
			$sCookieName=APP_NAME.'_language';
		}else{
			$sCookieName='language';
		}
		if(!$GLOBALS['_commonConfig_']['LANG_SWITCH']){
			$sLangSet=ucfirst(strtolower($GLOBALS['_commonConfig_']['LANG']));
		}elseif(isset($_GET['l'])){
			$sLangSet=ucfirst(strtolower($_GET['l']));
		}elseif($sCookieName){
			$sLangSet=Dyhb::cookie($sCookieName);
			if(empty($sLangSet)){
				$sLangSet=ucfirst(strtolower($GLOBALS['_commonConfig_']['LANG']));
			}
		}elseif($GLOBALS['_commonConfig_']['AUTO_ACCEPT_LANGUAGE'] && isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
			preg_match('/^([a-z\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'],$arrMatches);
			$sLangSet=ucfirst(strtolower($arrMatches[1]));
		}else{
			$sLangSet=ucfirst(strtolower($GLOBALS['_commonConfig_']['LANG']));
		}
		Dyhb::cookie($sCookieName,$sLangSet);
		define('LANG_NAME',$sLangSet);
		Lang::setCurrentLang($sLangSet);
		define('LANG_PATH',APP_LANG_PATH.'/'.LANG_NAME);
		define('LANG_PATH_DEFAULT',APP_LANG_PATH.'/'.ucfirst(strtolower($GLOBALS['_commonConfig_']['LANG'])));
		return;
	}
	static private function constantDefine(){
		define('__ENTER__',basename(__APP__));
		// 项目入口公用静态资源目录(也叫做公共目录)
		if(defined('__STATICS__')){
			define('__APPPUB__',__ROOT__.'/'.__STATICS__);
		}else{
			define('__APPPUB__',__ROOT__.'/'.APP_NAME.'/Static');
		}
		// 模板目录
		if(defined('__THEMES__')){
			define('__THEME__',__ROOT__.'/'.__THEMES__);
		}else{
			define('__THEME__',__ROOT__.'/'.APP_NAME.'/Theme');
		}
		// 项目资源目录
		define('__TMPL__',__THEME__.'/'.TEMPLATE_NAME);
		define('__TMPL__DEFAULT__',__THEME__.'/Default');
		// 网站公共文件目录
		define('__PUBLIC__',__ROOT__.'/Public');
		// 项目公共文件目录
		define('__TMPLPUB__',__TMPL__.'/Public');
		define('__TMPLPUB__DEFAULT__',__TMPL__DEFAULT__.'/Public');
		// 框架一个特殊的模块定义
		define('MODULE_NAME2',$GLOBALS['_commonConfig_']['TMPL_MODULE_ACTION_DEPR']=='/' && MODULE_NAME==='public'?'Public':MODULE_NAME);
		// 当前文件路径
		define('__TMPL_FILE_NAME__',__TMPL__.'/'.MODULE_NAME2.$GLOBALS['_commonConfig_']['TMPL_MODULE_ACTION_DEPR'].
			ACTION_NAME.$GLOBALS['_commonConfig_']['TEMPLATE_SUFFIX']
		);
		define('__TMPL_FILE_PATH__',TEMPLATE_PATH.'/'.MODULE_NAME2.$GLOBALS['_commonConfig_']['TMPL_MODULE_ACTION_DEPR'].
			ACTION_NAME.$GLOBALS['_commonConfig_']['TEMPLATE_SUFFIX']
		);
	}
	static public function U(){
		return "var _ROOT_='".__ROOT__."',_MODULE_NAME_='".MODULE_NAME."',_ACTION_NAME_='".ACTION_NAME."',_APP_NAME_ ='".APP_NAME."',_ENTER_ ='".__ENTER__.
			"',_APP_VAR_NAME_='app',_CONTROL_VAR_NAME_='c',_ACTION_VAR_NAME_='a',_URL_HTML_SUFFIX_='".
			$GLOBALS['_commonConfig_']['URL_HTML_SUFFIX']."';";
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   全局数据验证器($)*/
class Check{
	const SKIP_ON_FAILED='skip_on_failed';
	const SKIP_OTHERS='skip_others';
	const PASSED=true;
	const FAILED=false;
	const CHECK_ALL=true;
	protected static $_bIsError=false;
	protected static $_sErrorMessage;
	protected static $_oDefaultDbIns=null;
	private function __construct(){}
	public static function RUN($bDefaultIns=true){
		if($bDefaultIns and self::$_oDefaultDbIns){
			return self::$_oDefaultDbIns;
		}
		$oCheck=new self();
		if($bDefaultIns){
			self::$_oDefaultDbIns=$oCheck;
		}
		return $oCheck;
	}
	public static function C($Data,$Check){
		$arrArgs=func_get_args();
		unset($arrArgs[1]);
		$bResult=self::checkByArgs($Check,$arrArgs);
		return(bool)$bResult;
	}
	public static function checkBatch($Data,array $arrChecks,$bCheckAll=true,&$arrFailed=null){
		$bResult=true;
		$arrFailed=array();
		foreach($arrChecks as $arrV){
			$sVf=$arrV[0];
			$arrV[0]=$Data;
			$bRet=self::checkByArgs($sVf,$arrV);
			if($bRet===self::SKIP_OTHERS){// 跳过余下的验证规则
				return $bResult;
			}
			if($bRet===self::SKIP_ON_FAILED){
				$bCheckAll=false;
				continue;
			}
			if($bRet){
				continue;
			}
			$arrFailed[]=$arrV;
			$bResult=$bResult && $bRet;
			if(!$bResult && !$bCheckAll){
				return false;
			}
		}
		return(bool)$bResult;
	}
	public static function checkByArgs($Check,array $arrArgs){
		static $arrInternalFuncs=null;
		
		if(is_null($arrInternalFuncs)){
			$arrInternalFuncs=array('alnum','alpha','ascii','between','binary','cntrl','currency','date','datetime','digit',
									'domain','double','email','english','en','equal','eq','float','graph','greater_or_equal',
									'egt','gt','in','int2','integer','int','ip','ipv4','less_or_equal','elt','less_than','lt',
									'lower','max','min','mobile','not_empty','not_null','not_same','num','number','number_underline_english',
									'num_underline_en','num_un_en','n_u_e','nue','octal','phone','print','punct','regex','require',
									'same','empty','error','null','strlen','time','type','upper','url','url2','whitechspace',
									'xdigits','zip','rar','max_len','max_length','min_len','min_length');
			$arrInternalFuncs=array_flip($arrInternalFuncs);
		}
		self::$_bIsError=false;// 验证前还原状态
		if(!is_array($Check) && isset($arrInternalFuncs[$Check])){// 内置验证方法
			$bResult=call_user_func_array(array(__CLASS__,$Check.'_'),$arrArgs);
		}elseif(is_array($Check) || function_exists($Check)){// 使用回调处理
			$bResult=call_user_func_array($Check,$arrArgs);
		}elseif(strpos($Check,'::')){// 使用::回调处理
			$bResult=call_user_func_array(explode('::', $Check),$arrArgs);
		}else{// 错误的验证规则
			self::$_sErrorMessage=Dyhb::L('不存在的验证规则','__DYHB__@Dyhb');
			self::$_bIsError=true;
			return false;
		}
		if($bResult===false){
			self::$_sErrorMessage=Dyhb::L('验证数据出错','__DYHB__@Dyhb');
			self::$_bIsError=true;
		}
		return $bResult;
	}
	public static function alnum_($Data){
		return ctype_alnum($Data);
	}
	public static function alpha_($Data){
		return ctype_alpha($Data);
	}
	public static function ascii_($Data){
		return preg_match('/[^\x20-\x7f]/',$Data);
	}
	public static function between_($Data,$Min,$Max,$bInclusive=true){
		if($bInclusive){
			return $Data>=$Min && $Data<=$Max;
		}else{
			return $Data>$Min && $Data<$Max;
		}
	}
	public static function binary_($Data){
		return preg_match('/[01]+/',$Data);
	}
	public static function cntrl_($Data){
		return ctype_cntrl($Data);
	}
	public static function currency_($Data){
		return preg_match('/^\d+(\.\d+)?$/',$Data);
	}
	public static function date_($Data){
		if(strpos($Data,'-')!==false){// 分析数据中关键符号
			$sP='-';
		}elseif(strpos($Data,'/')!==false){
			$sP='\/';
		}else{
			$sP=false;
		}
		if($sP!==false and  preg_match('/^\d{4}'.$sP.'\d{1,2}'.$sP.'\d{1,2}$/',$Data)){
			$arrValue=explode($sP,$Data);
			if(count($Data)>=3){
				list($nYear,$nMonth,$nDay)=$arrValue;
				if(checkdate($nMonth,$nDay,$nYear)){
					return true;
				}
			}
		}
		return false;
	}
	public static function datetime_($Data){
		$test=@strtotime($Data);
		if($test!==false && $test!==-1){
			return true;
		}
		return false;
	}
	public static function digit_($Data){
		return ctype_digit($Data);
	}
	public static function domain_($Data){
		return preg_match('/[a-z0-9\.]+/i',$Data);
	}
	public static function double_($Data){
		return preg_match('/^[-\+]?\d+(\.\d+)?$/',$Data);
	}
	public static function email_($Data){
		return preg_match('/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i',$Data);
	}
	public static function english_($Data){
		return preg_match('/^[A-Za-z]+$/',$Data);
	}
	public static function en_($Data){
		return self::english_($Data);
	}
	public static function equal_($Data,$Test){
		return $Data==$Test;
	}
	public static function eq_($Data,$Test){
		return self::equal_($Data,$Test);
	}
	public static function float_($Data){
		static $arrLocale=null;
		
		if(is_null($arrLocale)){
			$arrLocale=localeconv();
		}
		$Data=str_replace($arrLocale['decimal_point'],'.',$Data);
		$Data=str_replace($arrLocale['thousands_sep'],'',$Data);
		if(strval(floatval($Data))==$Data){
			return true;
		}
		return false;
	}
	public static function graph_($Data){
		return ctype_graph($Data);
	}
	public static function greater_or_equal_($Data,$Test,$bInclusive=true){
		if($bInclusive){
			return $Data>=$Test;
		}else{
			return $Data>$Test;
		}
	}
	public static function egt_($Data,$Test,$bInclusive=true){
		return self::greater_or_equal_($Data,$Test,$bInclusive);
	}
	public static function gt_($Data,$Test){
		return self::greater_or_equal_($Data,$Test,false);
	}
	public static function in_($Data,$arrIn){
		return is_array($arrIn) and in_array($Data,$arrIn);
	}
	public static function int2_($Data){
		static $arrLocale=null;
		
		if(is_null($arrLocale)){
			$arrLocale=localeconv();
		}
		$Data=str_replace($arrLocale['decimal_point'],'.',$Data);
		$Data=str_replace($arrLocale['thousands_sep'],'',$Data);
		if(strval(intval($Data))==$Data){
			return true;
		}
		return false;
	}
	public static function integer_($Data){
		return preg_match('/^[-\+]?\d+$/',$Data);
	}
	public static function int_($Data,$Test){
		return self::integer_($Data);
	}
	public static function ip_($Data){
		return preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/',$Data);
	}
	public static function ipv4_($Data){
		$test=@ip2long($Data);
		if($test!==-1 and $test!==false){
			return true;
		}
		return false;
	}
	public static function less_or_equal_($Data,$Test,$bInclusive=true){
		if($bInclusive){
			return $Data<=$Test;
		}else{
			return $Data<$Test;
		}
	}
	public static function elt_($Data,$Test,$bInclusive=true){
		return self::less_or_equal_($Data,$Test,$bInclusive);
	}
	public static function less_than_($Data,$Test){
		return self::less_or_equal_($Data,$Test,false);
	}
	public static function lt_($Data,$Test){
		return self::less_or_equal_($Data,$Test,false);
	}
	public static function lower_($Data){
		return ctype_lower($Data);
	}
	public static function max_($Data,$Test){
		return $Data<=$Test;
	}
	public static function min_($Data,$Test){
		return $Data>=$Test;
	}
	public static function mobile_($Data){
		return preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?13\d{9}$/',$Data);
	}
	public static function not_empty_($Data){
		return !empty($Data);
	}
	public static function not_equal_($Data,$Test){
		return $Data!=$Test;
	}
	public static function neq_($Data,$Test){
		return self::not_equal_($Data,$Test);
	}
	public static function not_null_($Data){
		return !is_null($Data);
	}
	public static function not_same_($Data,$Test){
		return $Data!==$Test;
	}
	public static function num_($Data){
		return ($Data && preg_match('/\d+$/',$Data)) || !preg_match("/[^\d-.,]/",$Data) || $Data==0;
	}
	public static function number_($Data){
		return self::num_($Data);
	}
	public static function number_underline_english_($Data){
		return preg_match('/^[a-z0-9\-\_]*[a-z\-_]+[a-z0-9\-\_]*$/i',$Data);
	}
	public static function num_underline_en_($Data){
		return self::number_underline_english_($Data);
	}
	public static function num_un_en_($Data){
		return self::number_underline_english_($Data);
	}
	public static function n_u_e_($Data){
		return self::number_underline_english_($Data);
	}
	public static function nue_($Data){
		return self::number_underline_english_($Data);
	}
	public static function octal_($Data){
		return preg_match('/0[0-7]+/',$Data);
	}
	public static function phone_($Data){
		return preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/',$Data);
	}
	public static function print_($Data){
		return ctype_print($Data);
	}
	public static function punct_($Data){
		return ctype_punct($Data);
	}
	public static function regex_($Data,$sRegex){
		return preg_match($sRegex,$Data)>0;
	}
	public static function require_($Data){
		return preg_match('/.+/',$Data);
	}
	public static function same_($Data,$Test){
		return $Data===$Test;
	}
	public static function empty_($Data){
		return (strlen($Data)==0)?self::SKIP_OTHERS:true;
	}
	public static function error_($Data){
		return self::SKIP_ON_FAILED;
	}
	public static function null_($Data){
		return (is_null($Data))?self::SKIP_OTHERS:true;
	}
	public static function strlen_($Data,$nLen){
		return strlen($Data)==(int)$nLen;
	}
	public static function time_($Data){
		$arrParts=explode(':',$Data);
		$nCount=count($arrParts);
		if($nCount==2 || $nCount==3){
			if($nCount==2){
				$arrParts[2]='00';
			}
			$test=@strtotime($arrParts[0].':'.$arrParts[1].':'.$arrParts[2]);
			if($test!==-1 && $test!==false && date('H:i:s')==$Data){
				return true;
			}
		}
		return false;
	}
	public static function type_($Data,$Test){
		return gettype($Data)==$Test;
	}
	public static function upper_($Data){
		return ctype_upper($Data);
	}
	public static function url_($Data){
		return preg_match('/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/',$Data);
	}
	public static function url2_($Data){
		return preg_match("/(https?){1}:\/\/|www\.([^\[\"']+?)?/i",$Data);
	}
	public static function whitechspace_($Data){
		return ctype_space($Data);
	}
	public static function xdigits_($Data){
		return ctype_xdigit($Data);
	}
	public static function zip_($Data){
		return preg_match('/^[1-9]\d{5}$/',$Data);
	}
	public static function rar_($Data){
		return self::zip_($Data);
	}
	public static function min_length_($Data,$nLen){
		return strlen($Data)>=$nLen;
	}
	public static function min_len_($Data,$nLen){
		return self::min_length_($Data,$nLen);
	}
	public static function max_length_($Data,$nLen){
		return strlen($Data)<=$nLen;
	}
	public static function max_len_($Data,$nLen){
		return self::max_length_($Data,$nLen);
	}
	public static function isError(){
		return self::$_bIsError;
	}
	public static function getErrorMessage(){
		return self::$_sErrorMessage;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   控制器($)*/
class Controller{
	protected $_oView=null;
	public function __construct(){
		$this->_oView=new View($this);
	}
	public function init__(){}
	public function assign($Name,$Value=''){
		$this->_oView->assign($Name,$Value);
	}
	public function __set($Name,$Value){
		$this->assign($Name,$Value);
	}
	public function get($sName){
		$sValue=$this->_oView->get($sName);
		return $sValue;
	}
	public function &__get($sName){
		$value=$this->get($sName);
		return $value;
	}
	public function display($sTemplateFile='',$sCharset='utf-8',$sContentType='text/html',$bReturn=false){
		return $this->_oView->display($sTemplateFile,$sCharset,$sContentType,$bReturn);
	}
	protected function G($sName,$sViewName=null){
		$value=$this->_oView->getVar($sName);
		return $value;
	}
	protected function isAjax(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
			if('xmlhttprequest'==strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])){return true;}
		}
		if(!empty($_POST['ajax']) || !empty($_GET['ajax'])){
			return true;
		}
		return false;
	}
	protected function E($sMessage='',$nDisplay=3,$bAjax=FALSE){
		$this->J($sMessage,0,$nDisplay,$bAjax);
	}
	protected function S($sMessage,$nDisplay=1,$bAjax=FALSE){
		$this->J($sMessage,1,$nDisplay,$bAjax);
	}
	protected function A($Data,$sInfo='',$nStatus=1,$nDisplay=1,$sType=''){
		$arrResult=array();
		$arrResult['status']=$nStatus;
		$arrResult['display']=$nDisplay;
		$arrResult['info']=$sInfo?$sInfo:Dyhb::L('Ajax未指定返回消息','__DYHB__@Dyhb');
		$arrResult['data']=$Data;
		if(empty($sType)){
			$sType=$GLOBALS['_commonConfig_']['DEFAULT_AJAX_RETURN'];
		}
		$arrResult['type']=$sType;
		if(strtoupper($sType)=='JSON'){// 返回JSON数据格式到客户端 包含状态信息
			header("Content-Type:text/html; charset=utf-8");
			exit(json_encode($arrResult));
		}elseif(strtoupper($sType)=='XML'){// 返回xml格式数据
			header("Content-Type:text/xml; charset=utf-8");
			exit(G::xmlEncode($arrResult));
		}elseif(strtoupper($sType)=='EVAL'){// 返回可执行的js脚本
			header("Content-Type:text/html; charset=utf-8");
			exit($Data);
		}else{}
	}
	protected function U($sUrl,$arrParams=array(),$nDelay=0,$sMsg=''){
		$sUrl=Dyhb::U($sUrl,$arrParams);
		G::urlGoTo($sUrl,$nDelay,$sMsg);
	}
	private function J($sMessage,$nStatus=1,$nDisplay=1,$bAjax=FALSE){
		// 判断是否为AJAX返回
		if($bAjax || $this->isAjax()){
			$this->A('',$sMessage,$nStatus,$nDisplay);
		}
		// 提示标题
		if(!$this->G('__MessageTitle__')){
			$this->assign('__MessageTitle__',$nStatus?Dyhb::L('操作成功','__DYHB__@Dyhb'):Dyhb::L('操作失败','__DYHB__@Dyhb'));
		}
		// 关闭窗口
		if($this->G('__CloseWindow__')){
			$this->assign('__JumpUrl__','javascript:window.close();');
		}
		// 消息图片
		if(defined('__MESSAGE_IMG_PATH__')){
			$arrMessageImg=array(
				'loader'=>__MESSAGE_IMG_PATH__.'/loader.gif',
				'infobig'=>__MESSAGE_IMG_PATH__.'/info_big.gif',
				'errorbig'=>__MESSAGE_IMG_PATH__.'/error_big.gif'
			);
		}else{
			$arrMessageImg=array(
				'loader'=>'Public/Images/loader.gif',
				'infobig'=>'Public/Images/info_big.gif',
				'errorbig'=>'Public/Images/error_big.gif'
			);
			$bExists=is_file(TEMPLATE_PATH.'/Public/Images/loader.gif')?true:false;
			foreach($arrMessageImg as $sKey=>$sMessageImg){
				$arrMessageImg[$sKey]=$bExists===true?__TMPL__.'/'.$arrMessageImg[$sKey]:__THEME__.'/Default/'.$arrMessageImg[$sKey];
			}
		}
		$this->assign('__LoadingImg__',$arrMessageImg['loader']);
		$this->assign('__InfobigImg__',$arrMessageImg['infobig']);
		$this->assign('__ErrorbigImg__',$arrMessageImg['errorbig']);
		// 状态
		$this->assign('__Status__',$nStatus);
		if($nStatus){
			$this->assign('__Message__',$sMessage);// 提示信息
		}else{
			$this->assign('__ErrorMessage__',$sMessage);
		}
		$arrInit=array();
		if($nStatus){
			if(!$this->G('__WaitSecond__')){// 成功操作后默认停留1秒
				$this->assign('__WaitSecond__',1);
				$arrInit['__WaitSecond__']=1;
			}else{
				$arrInit['__WaitSecond__']=$this->G('__WaitSecond__');
			}
			if(!$this->G('__JumpUrl__')){// 默认操作成功自动返回操作前页面
				$this->assign('__JumpUrl__',isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:'');
				$arrInit['__JumpUrl__']=isset($_SERVER["HTTP_REFERER"])? $_SERVER["HTTP_REFERER"]:'';
			}else{
				$arrInit['__JumpUrl__']=$this->G('__JumpUrl__');
			}
			$sJavaScript=$this->javascriptR($arrInit);
			$this->assign('__JavaScript__',$sJavaScript);
			$sTemplate=strpos($GLOBALS['_commonConfig_']['TMPL_ACTION_SUCCESS'],'public+')===0 && $GLOBALS['_commonConfig_']['TMPL_MODULE_ACTION_DEPR']=='/'?
				str_replace('public+','Public+',$GLOBALS['_commonConfig_']['TMPL_ACTION_SUCCESS']):
				$GLOBALS['_commonConfig_']['TMPL_ACTION_SUCCESS'];
			$this->display($sTemplate);
		}else{
			if(!$this->G('__WaitSecond__')){// 发生错误时候默认停留3秒
				$this->assign('__WaitSecond__',3);
				$arrInit['__WaitSecond__']=3;
			}else{
				$arrInit['__WaitSecond__']=$this->G('__WaitSecond__');
			}
			if(!$this->G('__JumpUrl__')){// 默认发生错误的话自动返回上页
				if(preg_match('/(mozilla|m3gate|winwap|openwave)/i', $_SERVER['HTTP_USER_AGENT'])){
					$this->assign('__JumpUrl__','javascript:history.back(-1);');
				}else{// 手机
					$this->assign('__JumpUrl__',__APP__);
				}
				$arrInit['__JumpUrl__']='';
			}else{
				$arrInit['__JumpUrl__']=$this->G('__JumpUrl__');
			}
			$sJavaScript=$this->javascriptR($arrInit);
			$this->assign('__JavaScript__',$sJavaScript);
			$sTemplate=strpos($GLOBALS['_commonConfig_']['TMPL_ACTION_ERROR'],'public+')===0 && $GLOBALS['_commonConfig_']['TMPL_MODULE_ACTION_DEPR']=='/'?
				str_replace('public+','Public+',$GLOBALS['_commonConfig_']['TMPL_ACTION_ERROR']):
				$GLOBALS['_commonConfig_']['TMPL_ACTION_ERROR'];
			$this->display($sTemplate);
		}
		exit;
	}
	private function javascriptR($arrInit){
		extract($arrInit);
		return "<script type=\"text/javascript\">var nSeconds={$__WaitSecond__};var sDefaultUrl=\"{$__JumpUrl__}\";onload=function(){if((sDefaultUrl=='javascript:history.go(-1)' || sDefaultUrl=='') && window.history.length==0){document.getElementById('__JumpUrl__').innerHTML='';return;};window.setInterval(redirection,1000);};function redirection(){if(nSeconds<=0){window.clearInterval();return;};nSeconds --;document.getElementById('__Seconds__').innerHTML=nSeconds;if(nSeconds==0){document.getElementById('__Loader__').style.display='none';window.clearInterval();if(sDefaultUrl!=''){window.location.href=sDefaultUrl;}}}</script>";
	}
}
class SsmiController extends Controller{
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		if(!isset($_REQUEST['param'])){
			$arrParam=array();
			foreach($_REQUEST as $sKey=>$sVal){
				if(preg_match('/^param_(.+)/i',$sKey,$arrRes)){$arrParam[$arrRes[1]]=$sVal;}
			}
		}else{
			$arrParam =array($_REQUEST['param']);
		}
		call_user_func_array(array($_REQUEST['class'],$_REQUEST['method']),$arrParam);
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   视图管理类($)*/
class View{
	private $_oPar=null;
	private $_oTemplate;
	static private $_oShareGlobalTemplate;
	private $_sTemplateFile;
	private $_nRuntime;
	public function __construct($oPar,$sTemplate=null,$oTemplate=null){
		if($oTemplate){
			$this->setTemplate($oTemplate);
		}else{
			$this->setTemplate(self::createShareTemplate());
		}
		$this->_sTemplateFile=$sTemplate;
		$this->_oPar=$oPar;
		$this->init__();
	}
	public function init__(){}
	public function parseTemplateFile($sTemplateFile){
		$arrTemplateInfo=array();
		if(empty($sTemplateFile)){
			$sSuffix=$GLOBALS['_commonConfig_']['TEMPLATE_SUFFIX'];
			$arrTemplateInfo=array(
				'file'=>MODULE_NAME2.$GLOBALS['_commonConfig_']['TMPL_MODULE_ACTION_DEPR'].ACTION_NAME.$sSuffix
			);
		}elseif(!strpos($sTemplateFile,':\\') && strpos($sTemplateFile,'/')!==0 && !is_file($sTemplateFile)){// D:\phpcondition\......排除绝对路径分析
			if(strpos($sTemplateFile,'@')){// 分析主题
				$arrArray=explode('@',$sTemplateFile);
				$arrTemplateInfo['theme']=ucfirst(strtolower(array_shift($arrArray)));
				$sTemplateFile=array_shift($arrArray);
			}
			$sTemplateFile =str_replace('+',$GLOBALS['_commonConfig_']['TMPL_MODULE_ACTION_DEPR'],$sTemplateFile);//模块和操作&分析文件
			$sSuffix=$GLOBALS['_commonConfig_']['TEMPLATE_SUFFIX'];
			$arrTemplateInfo['file']=$sTemplateFile.$sSuffix;
		}
		if(!empty($arrTemplateInfo)){
			return $arrTemplateInfo;
		}else{
			return $sTemplateFile;
		}
	}
	public function getTemplate(){
		if(is_null($this->_oTemplate)){
			$this->_oTemplate=self::createShareTemplate();
		}
		return $this->_oTemplate;
	}
	public function setTemplate(Template $oTemplate){
		$oOldValue=$this->_oTemplate;
		$this->_oTemplate=$oTemplate;
		return $oOldValue;
	}
	public function getPar(){
		if($this->_oPar===null){
			return null;
		}else{
			return $this->_oPar;
		}
	}
	static public function createShareTemplate(){
		if(!self::$_oShareGlobalTemplate){
			self::$_oShareGlobalTemplate=new Template();
		}
		return self::$_oShareGlobalTemplate;
	}
	public function display($sTemplateFile='',$sCharset='utf-8',$sContentType='text/html',$bReturn=false){
		header("Content-Type:".$sContentType."; charset=".$sCharset);
		header("Cache-control: private");//支持页面回跳
		if(empty($sTemplateFile)){
			$sTemplateFile=&$this->_sTemplateFile;
		}
		$this->_nRuntime=G::getMicrotime();// 记录模板开始运行时间
		$TemplateFile=$sTemplateFile;
		if(!is_file($sTemplateFile)){
			$TemplateFile=$this->parseTemplateFile($sTemplateFile);
		}
		$oTemplate=$this->getTemplate();
		$oController=$this->getPar();
		$oTemplate->setVar('TheView',$this);
		$oTemplate->setVar('TheController',$oController);
		$sContent=$oTemplate->display($TemplateFile,false);
		// 如果开启静态化，则写入数据
		if($GLOBALS['_commonConfig_']['HTML_CACHE_ON']){
			Html::W($sContent);
		}
		if($GLOBALS['_commonConfig_']['SHOW_RUN_TIME']){
			$sContent.=$this->templateRuntime();
		}
		if($GLOBALS['_commonConfig_']['SHOW_PAGE_TRACE']){
			$sContent.=$this->templateTrace();
		}
		if($bReturn===true){
			return $sContent;
		}else{
			echo $sContent;
			unset($sContent);
		}
	}
	public function templateRuntime(){
		$sContent='<div id="dyhb_run_time" class="dyhb_run_time" style="display:none;">';
		// 总时间
		$nEndTime=microtime(TRUE);
		$nTotalRuntime=number_format(($nEndTime-$GLOBALS['_beginTime_']),3);
		$sContent.="Processed in ".$nTotalRuntime." second(s)";
		if($GLOBALS['_commonConfig_']['SHOW_DETAIL_TIME']){
			$sContent.="(Template:".$this->getMicrotime()." s)";
		}
		if($GLOBALS['_commonConfig_']['SHOW_DB_TIMES']){
			$oDb=Db::RUN();
			$sContent.=" | ".$oDb->getConnect()->Q()." queries";
		}
		if($GLOBALS['_commonConfig_']['SHOW_GZIP_STATUS']){
			if($GLOBALS['_commonConfig_']['START_GZIP']){
				$sGzipString='disabled';
			}else{
				$sGzipString='disabled';
			}
			$sContent.=" | Gzip {$sGzipString}";
		}
		if(MEMORY_LIMIT_ON && $GLOBALS['_commonConfig_']['SHOW_USE_MEM']){
			$nStartMem=array_sum(explode(' ',$GLOBALS['_startUseMems_']));
			$nEndMem=array_sum(explode(' ',memory_get_usage()));
			$sContent.=' | UseMem:'. G::changeFileSize($nEndMem-$nStartMem);
		}
		$sContent.="</div>";
		return $sContent;
	}
	public function templateTrace(){
		$arrTrace=array();
		$arrLog=Log::$_arrLog;
		$arrTrace[Dyhb::L('日志记录','__DYHB__@Dyhb')]=count($arrLog)?Dyhb::L('%d条日志','__DYHB__@Dyhb',null,count($arrLog)).'<br/>'.implode('<br/>',$arrLog):Dyhb::L('无日志记录','__DYHB__@Dyhb');
		$arrFiles= get_included_files();
		$arrTrace[Dyhb::L('加载文件','__DYHB__@Dyhb')]=count($arrFiles).str_replace("\n",'<br/>',substr(substr(print_r($arrFiles,true),7),0,-2));
		ob_start();
		include DYHB_PATH.'/Resource_/Template/PageTrace.template.php';
		$sContent=ob_get_contents();
		ob_end_clean();
		return $sContent;
	}
	public function getMicrotime(){
		return round(G::getMicrotime()-$this->_nRuntime,5);
	}
	public function assign($Name,$Value=null){
		$oTemplate=$this->getTemplate();
		return $oTemplate->setVar($Name,$Value);
	}
	public function get($Name){
		return $this->getVar($Name);
	}
	public function getVar($Name){
		$oTemplate=$this->getTemplate();
		return $oTemplate->getVar($Name);
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   模板处理类($)*/
class Template{
	protected $TEMPLATE_OBJS=array();
	static public $_arrParses=array();
	protected $_sCompiledFilePath;
	protected $_sThemeName='';
	protected $_bIsChildTemplate=FALSE;
	static protected $_bWithInTheSystem=FALSE;
	static private $_sTemplateDir;
	private $_arrVariable=array();
	public function loadParses(){
		$sClassName=get_class($this);// 具体的类
		call_user_func(array($sClassName,'loadDefaultParses'));// 载入默认的分析器
	}
	public function putInTemplateObj_(TemplateObj $oTemplateObj){
		$this->TEMPLATE_OBJS[]=$oTemplateObj;
	}
	public function clearTemplateObj(){
		$nCount=count($this->TEMPLATE_OBJS);
		$this->TEMPLATE_OBJS=array();
		return $nCount;
	}
	public function compile($sTemplatePath,$sCompiledPath=''){
		if(!is_file($sTemplatePath)){
			Dyhb::E('$sTemplatePath is not a file');
		}
		if($sCompiledPath==''){
			$sCompiledPath=$this->getCompiledPath($sTemplatePath);
		}
		$sCompiled=file_get_contents($sTemplatePath);
		foreach(self::$_arrParses as $sParserName){
			$oParser=Dyhb::instance($sParserName);
			$this->bParseTemplate_($sCompiled);
			$oParser->parse($this,$sTemplatePath,$sCompiled);// 分析
			$sCompiled=$this->compileTemplateObj();// 编译
		}
		if(defined('TMPL_STRIP_SPACE')){
			// HTML
			$arrFind=array("~>\s+<~","~>(\s+\n|\r)~");
			$arrReplace=array("><",">");
			$sCompiled=preg_replace($arrFind,$arrReplace,$sCompiled);
			// Javascript
			$sCompiled=preg_replace(array('/(^|\r|\n)\/\*.+?(\r|\n)\*\/(\r|\n)/is','/\/\/note.+?(\r|\n)/i','/\/\/debug.+?(\r|\n)/i','/(^|\r|\n)(\s|\t)+/','/(\r|\n)/',"/\/\*(.*?)\*\//ies"),'',$sCompiled);
		}
		$sStr="<?php  /* DoYouHaoBaby Framework ".(Dyhb::L('模板缓存文件生成时间：','__DYHB__@Dyhb')).date('Y-m-d H:i:s',CURRENT_TIMESTAMP)."  */ ?>\r\n";
		$sCompiled=$sStr.$sCompiled;
		$sCompiled=str_replace(array("\r","\n"),'
',$sCompiled);
		$sCompiled=preg_replace("/(
)+/i",'
',$sCompiled);
		$sCompiled=str_replace('
',(IS_WIN?"\r\n":"\n"),$sCompiled);// 解决不同操作系统源代码换行混乱
		$this->makeCompiledFile($sTemplatePath,$sCompiledPath,$sCompiled);// 生成编译文件
		return $sCompiledPath;
	}
	protected function compileTemplateObj(){
		$sCompiled='';// 逐个编译TemplateObj
		foreach($this->TEMPLATE_OBJS as $oTemplateObj){
			$oTemplateObj->compile();
			$sCompiled.=$oTemplateObj->getCompiled();
		}
		return $sCompiled;
	}
	public function getCompiledPath($sTemplatePath){
		$sTemplatePath=str_replace('\\','/',$sTemplatePath); 
		
		$arrValue=explode('/',str_replace(array(str_replace('\\','/',TEMPLATE_PATH.'/'),str_replace('\\','/',DYHB_PATH.'/'),str_replace('\\','/',getcwd().'/')),array(''),$sTemplatePath));
		if($GLOBALS['_commonConfig_']['TMPL_MODULE_ACTION_DEPR']=='/' && count($arrValue)>1){
			array_shift($arrValue);
		}
		
		if(self::$_bWithInTheSystem===true){// 如果保存在系统内部
			$this->_sCompiledFilePath=dirname($sTemplatePath).'/Compiled/'.basename($sTemplatePath).'.compiled.php';
			return $this->_sCompiledFilePath;
		}
		$sFileName=implode('/',$arrValue);
		$this->_sCompiledFilePath=APP_RUNTIME_PATH.'/Cache/'.($this->_sThemeName?ucfirst($this->_sThemeName).'/':'').
			($GLOBALS['_commonConfig_']['TMPL_MODULE_ACTION_DEPR']=='/'?ucfirst(MODULE_NAME).'/':'').
			$sFileName.'.compiled.php';
		return $this->_sCompiledFilePath;
	}
	static public function in($bWithInTheSystem=false){
		$bOldValue=self::$_bWithInTheSystem;
		self::$_bWithInTheSystem=$bWithInTheSystem;
		return $bOldValue;
	}
	public function returnCompiledPath(){
		return $this->_sCompiledFilePath;
	}
	protected function isCompiledFileExpired($sTemplatePath,$sCompiledPath){
		if(!is_file($sCompiledPath)){
			return true;
		}
		if($GLOBALS['_commonConfig_']['CACHE_LIFE_TIME']==-1){// 编译过期时间为-1表示永不过期
			return false;
		}
		if(filemtime($sCompiledPath)+$GLOBALS['_commonConfig_']['CACHE_LIFE_TIME']<CURRENT_TIMESTAMP){
			return true;
		}
		if(filemtime($sTemplatePath)>=filemtime($sCompiledPath)){
			return true;
		}
		return false;
	}
	protected function makeCompiledFile($sTemplatePath,$sCompiledPath,&$sCompiled){
		!is_file($sCompiledPath) && !is_dir(dirname($sCompiledPath)) && G::makeDir(dirname($sCompiledPath));
		file_put_contents($sCompiledPath,$sCompiled);
	}
	static public function loadDefaultParses(){
		include_once(DYHB_PATH.'/Template/Parser/TemplateParsers_.php');
		TemplateGlobalParser::regToParser();// 全局
		TemplatePhpParser::regToParser();// PHP
		TemplateCodeParser::regToParser();// 代码
		TemplateNodeParser::regToParser();// 节点
		TemplateRevertParser::regToParser(); // 反向
		TemplateGlobalRevertParser::regToParser(); // 全局反向
	}
	static public function setTemplateDir($sDir){
		if(!is_dir($sDir)){
			Dyhb::E('$sDir is not a dir');
		}
		return self::$_sTemplateDir=$sDir;
	}
	static public function findTemplate($arrTemplateFile){
		$sTemplateFile=isset($arrTemplateFile['theme'])?$arrTemplateFile['theme'].'/':'';
		$sTemplateFile.=(isset($arrTemplateFile['file'])?$arrTemplateFile['file']:'');
		if(is_file(self::$_sTemplateDir.'/'.$sTemplateFile)){
			return self::$_sTemplateDir.'/'.$sTemplateFile;
		}
		if(defined('DOYOUHAOBABY_TEMPLATE_BASE') && !isset($arrTemplateFile['theme']) && ucfirst(DOYOUHAOBABY_TEMPLATE_BASE)!==TEMPLATE_NAME){// 依赖模板 兼容性分析
			$sTemplateDir=str_replace('/Theme/'.TEMPLATE_NAME.'/','/Theme/'.ucfirst(DOYOUHAOBABY_TEMPLATE_BASE).'/',self::$_sTemplateDir.'/');
			if(is_file($sTemplateDir.'/'.$sTemplateFile)){
				return $sTemplateDir.'/'.$sTemplateFile;
			}
		}
		if(!isset($arrTemplateFile['theme']) && 'Default'!==TEMPLATE_NAME){// Default模板 兼容性分析
			$sTemplateDir=str_replace('/Theme/'.TEMPLATE_NAME.'/','/Theme/Default/',self::$_sTemplateDir.'/');
			if(is_file($sTemplateDir.'/'.$sTemplateFile)){
				return $sTemplateDir.$sTemplateFile;
			}
		}
		return null;
	}
	public function putInTemplateObj(TemplateObj $oTemplateObj){
		$oTopTemplateObj=$this->TEMPLATE_OBJS[0];
		$oTopTemplateObj->addTemplateObj($oTemplateObj);// 插入
	}
	protected function bParseTemplate_(&$sCompiled){
		$oTopTemplateObj=new TemplateObj($sCompiled);// 创建顶级TemplateObj
		$oTopTemplateObj->locate($sCompiled,0);
		$this->clearTemplateObj();
		Template::putInTemplateObj_($oTopTemplateObj);
	}
	public function includeChildTemplate($sTemplateFile){
		if(!is_file($sTemplateFile)){
			$bExistsFile=false;// 默认主题自动导向
			if(defined('DOYOUHAOBABY_TEMPLATE_BASE') && ucfirst(DOYOUHAOBABY_TEMPLATE_BASE)!==TEMPLATE_NAME){// 依赖主题自动导向
				$sReplacePath='/Theme/'.TEMPLATE_NAME.'/';
				$sTargetPath='/Theme/'.ucfirst(DOYOUHAOBABY_TEMPLATE_BASE).'/';
				$sTemplateFile2=str_replace($sReplacePath,$sTargetPath,$sTemplateFile);
				if(is_file($sTemplateFile2)){
					$sTemplateFile=&$sTemplateFile2;
					$bExistsFile=true;
				}else{
					unset($sTemplateFile2);
				}
			}
			if($bExistsFile===false && 'Default'!==TEMPLATE_NAME){// 默认主题自动导向
				$sReplacePath='/Theme/'.TEMPLATE_NAME.'/';
				$sTargetPath='/Theme/Default/';
				$sTemplateFile=str_replace($sReplacePath,$sTargetPath,$sTemplateFile);
				if(is_file($sTemplateFile)){
					$bExistsFile=true;
				}
			}
			if($bExistsFile===false){
				E(Dyhb::L('警告：对不起子模板 %s 不存在','__DYHB__@Dyhb',null,$sTemplateFile));
				return;
			}
		}
		$this->display($sTemplateFile,true);
	}
	public function display($TemplateFile,$bDisplayAtOnce=true){
		$TemplateFileOld=$TemplateFile;
		if(is_string($TemplateFile) && is_file($TemplateFile)){
			$this->_sThemeName=TEMPLATE_NAME;
		}else{
			if(is_array($TemplateFile) && !empty($TemplateFile['theme'])){
				$this->_sThemeName=$TemplateFile['theme'];
			}else{
				$this->_sThemeName=TEMPLATE_NAME;
			}
			$TemplateFile=self::findTemplate($TemplateFile);
		}
		if(!is_file($TemplateFile)){
			$TemplateFile=$TemplateFile?$TemplateFile:$TemplateFileOld;
			Dyhb::E(Dyhb::L('无法找到模板文件<br>%s','__DYHB__@Dyhb',null,is_array($TemplateFile)?implode(' ',$TemplateFile):$TemplateFile));
		}
		$arrVars=&$this->_arrVariable;
		if(is_array($arrVars) and count($arrVars)){
			extract($arrVars,EXTR_PREFIX_SAME,'tpl_');
		}
		$sCompiledPath=$this->getCompiledPath($TemplateFile);// 编译文件路径
		if($this->isCompiledFileExpired($TemplateFile,$sCompiledPath)){// 重新编译
			$this->loadParses();
			$this->compile($TemplateFile,$sCompiledPath);
		}
		$sReturn=null;
		if($bDisplayAtOnce===false){// 需要返回
			ob_start();
			include $sCompiledPath;
			$sReturn=ob_get_contents();
			ob_end_clean();
			return $sReturn;
		}else{// 不需要返回
			include $sCompiledPath;
		}
		return $sReturn;
	}
	public function setVar($Name,$Value=null){
		if(is_string($Name)){
			$sOldValue=isset($this->_arrVariable[$Name])?$this->_arrVariable[$Name]:null;
			$this->_arrVariable[$Name]=&$Value;
			return $sOldValue;
		}elseif(is_array($Name)){
			foreach($Name as $sName=>&$EachValue){
				$this->setVar($sName,$EachValue);
			}
		}
	}
	public function getVar($sName){
		return isset($this->_arrVariable[$sName])?$this->_arrVariable[$sName]:null;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   对 PHP 原生Cookie 函数库的封装($)*/
class Cookie{
	public static function setCookie($sName,$sValue='',$nLife=0,$bPrefix=true,$bHttponly=false){
		$sName=($bPrefix?$GLOBALS['_commonConfig_']['COOKIE_PREFIX']:'').$sName;
		if($sValue=='' || $nLife<0){
			$sValue='';
			$nLife=-1;
			if(isset($_COOKIE[$sName])){
				unset($_COOKIE[$sName]);
			}
		}else{
			$sValue=base64_encode(serialize($sValue));
			$_COOKIE[$sName]=$sValue;
			if($nLife!==NULL && $nLife==0){
				$nLife=$GLOBALS['_commonConfig_']['COOKIE_EXPIRE'];
			}
		}
		$nLife=$nLife>0?CURRENT_TIMESTAMP+$nLife:($nLife<0?CURRENT_TIMESTAMP-31536000:null);
		$sPath=$bHttponly && PHP_VERSION<'5.2.0'?$GLOBALS['_commonConfig_']['COOKIE_PATH'].';HttpOnly':$GLOBALS['_commonConfig_']['COOKIE_PATH'];
	
		$nSecure=$_SERVER['SERVER_PORT']==443?1:0;
		if(PHP_VERSION<'5.2.0'){
			setcookie($sName,$sValue,$nLife,$sPath,$GLOBALS['_commonConfig_']['COOKIE_DOMAIN'],$nSecure);
		}else{
			setcookie($sName,$sValue,$nLife,$sPath,$GLOBALS['_commonConfig_']['COOKIE_DOMAIN'],$nSecure,$bHttponly);
		}
	}
	public static function getCookie($sName,$bPrefix=true){
		$sName=($bPrefix?$GLOBALS['_commonConfig_']['COOKIE_PREFIX']:'').$sName;
		return isset($_COOKIE[$sName])?unserialize(base64_decode($_COOKIE[$sName])):'';
	}
	public static function deleteCookie($sName,$bPrefix=true){
		self::setCookie($sName,null,-1,$bPrefix);
	}
	public static function clearCookie($bOnlyDeletePrefix=true){
		$nCookie=count($_COOKIE);
		foreach($_COOKIE as $sKey=>$Val){
			if($bOnlyDeletePrefix===true && $GLOBALS['_commonConfig_']['COOKIE_PREFIX']){
				if(strpos($sKey,$GLOBALS['_commonConfig_']['COOKIE_PREFIX'])===0){
					self::setCookie($sKey,null,-1);
				}
			}else{
				self::setCookie($sKey,null,-1);
			}
		}
		return $nCookie;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   模型($)*/
interface IModel{}
class Model implements IModel,IModelCallback,ArrayAccess{
	const AUTOFILL_DATETIME='DATETIME';
	const AUTOFILL_TIMESTAMP='TIMESTAMP';
	const AUTOFILL_DATE='DATE_'; // 与系统函数date()冲突 ，所以我们在后面加了一个‘_’分割
	const AUTOFILL_TIME='TIME_'; // 与系统函数time()冲突
	const MODEL_ALL='ALL'; // 任何时间填充
	const MODEL_CREATE='CREATE'; // 创建的时候填充
	const MODEL_UPDATE='UPDATE'; // 更新的时候填充
	const EXISTS_TO_CHECKDATE='EXIST'; // 存在字段即验证
	const MUST_TO_CHECKDATE='MUST'; // 必须验证
	const VALUE_TO_CHECKDATE='NOTEMPTY'; // 不为空即验证
	const FIELD_DATELINE='DATELINE'; // 任何时候用当前Linux时间戳进行填充
	const FIELD_CREATE_DATELINE='CREATE_DATELINE'; // 创建对象的时候使用当前Linux时间戳进行填充
	const FIELD_UPDATE_DATELINE='UPDATE_DATELINE'; // 更新对象的时候使用当前Linux时间戳进行填充
	protected $_bIsError=false;
	protected $_sErrorMessage;
	protected $_arrProp;
	protected $_sClassName;
	private static $_arrMeta;
	private $_arrChangedProp=array();
	protected $_id=false;
	protected $_bAutofill=true;
	public function __construct($Data=null,$sNamesStyle=Db::PROP,$bFromStorage=FALSE,$sName=''){
		// 设置模型名字
		if(empty($sName)){
			$sName=get_class($this);
		}
		$this->_sClassName=$sName;
		if(empty($sNamesStyle)){
			$sNamesStyle=Db::PROP;
		}
		// 判断是否存在Meta对象，否则创建
		if(!isset(self::$_arrMeta[$this->_sClassName])){
			self::$_arrMeta[$this->_sClassName]=ModelMeta::instance($this->_sClassName);
		}
		$oMeta=self::$_arrMeta[$this->_sClassName];
		$this->_arrProp=$oMeta->_arrDefaultProp;
		if($Data!==null){// 如果$Data不为NULL，则改变属性值
			$this->changeProp($Data,$sNamesStyle,null,$bFromStorage,true);
		}
		$this->afterInit_();
		$this->event_(self::AFTER_INIT);
		$this->afterInitPost_();
	}
	public function id($bCached=true){
		if($bCached && $this->_id!==false){
			return $this->_id;
		}
		$arrId=array();
		if(is_array(self::$_arrMeta[$this->_sClassName]->_arrIdName)){
			foreach(self::$_arrMeta[$this->_sClassName]->_arrIdName as $sName){
				$arrId[$sName]=$this->{$sName};
			}
		}
		if(count($arrId)==1){
			$arrId=reset($arrId);
		}
		$this->_id=$arrId;
		return $arrId;
	}
	public function save($nRecursion=99,$sSaveMethod='save'){
		// 自动填充
		$this->makePostData();
		// 指定继承类名称的字段名
		$sInheritTypeField=self::$_arrMeta[$this->_sClassName]->_sInheritTypeField;
		if($sInheritTypeField && empty($this->_arrProp[$sInheritTypeField ])){
			$this->_arrProp[$sInheritTypeField]=$this->_sClassName;
		}
		$this->beforeSave_();// 触发保存数据前事件
		$this->event_(self::BEFORE_SAVE);
		$this->beforeSavePost_();
		$arrId=$this->id(false); // 不使用缓存
		// 程序通过内置方法统一实现
		switch (strtolower($sSaveMethod)){
			case 'create':
				$this->create_($nRecursion);
				break;
			case 'update':
				$this->update_($nRecursion);
				break;
			case 'replace':
				$this->replace_($nRecursion);
				break;
			case 'save':
				default:
				if(!is_array($arrId)){
					if(empty($arrId)){// 单一主键的情况下，如果 $arrId 为空，则 create，否则 update
						$this->create_($nRecursion);
					}else{
						$this->update_($nRecursion);
					}
				}else{
					$this->replace_($nRecursion);// 复合主键的情况下，则使用 replace 方式
				}
				break;
		}
		// 触发保存后的事件
		$this->afterSave_();
		$this->event_(self::AFTER_SAVE);
		$this->_id=false; // 清除缓存
		return $this;
	}
	public function changePropForce($sPropName,$PropValue){
		$oMeta=self::$_arrMeta[$this->_sClassName];
		if(!isset($oMeta->_arrProp[$sPropName])){
			return $this;
		}
		$bRo=$oMeta->_arrProp[$sPropName]['readonly'];
		self::$_arrMeta[$this->_sClassName]->_arrProp[$sPropName]['readonly']=false;
		$this->{$sPropName}=$PropValue;
		self::$_arrMeta[$this->_sClassName]->_arrProp[$sPropName]['readonly']=$bRo;
		return $this;
	}
	public function changeProp($Prop,$sNamesStyle=Db::PROP,$AttrAccessible=null,$bFromStorage=false,$bIignoreReadonly=false){
		$oMeta=self::$_arrMeta[$this->_sClassName];
		if($AttrAccessible){
			$AttrAccessible=array_flip(Dyhb::normalize($AttrAccessible));
			$bCheckAttrAccessible=true;
		}else{
			$bCheckAttrAccessible=!empty($oMeta->_arrAttrAccessible);
			$AttrAccessible=$oMeta->_arrAttrAccessible;
		}
		if(is_array($Prop)){
			foreach($Prop as $sPropName=>$Value){// 将数组赋值给对象属性
				if($sNamesStyle==Db::FIELD){// 如果是字段，那么建立与元的映射，这样方便后面判断那些数据需要被写入数据库
					if(!isset($oMeta->_arrFieldToProp[$sPropName])){// 字段到属性的映射
						continue;
					}
					$sPropName=$oMeta->_arrFieldToProp[$sPropName];
				}elseif(!isset($oMeta->_arrProp[$sPropName])){
					continue;
				}
				if($bFromStorage){
					if($oMeta->_arrProp[$sPropName]['virtual']){
						$this->{$sPropName}=$Value;
						if(isset($this->_arrChangedProp[$sPropName])){
							unset($this->_arrChangedProp[$sPropName]);
						}
					}else{
						$this->_arrProp[$sPropName]=is_null($Value)?NULL:$this->dbFieldtypeCheck_($sPropName,$Value);
					}
				}else{
					if($bCheckAttrAccessible){
						if(!isset($AttrAccessible[$sPropName])){
							continue;
						}
					}elseif(isset($oMeta->_arrAttrProtected[$sPropName])){
						continue;
					}
					if($bIignoreReadonly){
						$this->changePropForce($sPropName,$Value);
					}else{
						$this->{$sPropName}=$Value;
					}
				}
			}
		}
		return $this;
	}
	public function get($sPropName){
		return $this->__get($sPropName);
	}
	public function &__get($sPropName){
		if(!isset(self::$_arrMeta[$this->_sClassName]->_arrProp[$sPropName])){
			Dyhb::E(Dyhb::L('属性：%s不存在。','__DYHB__@Dyhb',null,$sPropName));
		}
		$arrConfig=self::$_arrMeta[$this->_sClassName]->_arrProp[$sPropName];
		// 如果指定了属性的 getter，则通过 getter 方法来获得属性值
		if(!empty($arrConfig['getter'])){
			list($callback,$arrCustomParameters)=$arrConfig['getter'];
			if(!is_array($callback)){
				$callback=array($this,$callback);
				$arrArgs=array($sPropName,$arrCustomParameters,&$this->_arrProp);
			}else{
				$arrArgs=array($this,$sPropName,$arrCustomParameters,&$this->_arrProp);
			}
			return call_user_func_array($callback,$arrArgs);
		}
		if(!isset($this->_arrProp[$sPropName]) && $arrConfig['relation']){
			$this->_arrProp[$sPropName]=self::$_arrMeta[$this->_sClassName]->relatedObj($this,$sPropName);
		}
		return $this->_arrProp[$sPropName];
	}
	public function set($sPropName,$Value){
		$this->__set($sPropName,$Value);
	}
	public function __set($sPropName,$Value){
		$oMeta=self::$_arrMeta[$this->_sClassName];
		if(!isset($oMeta->_arrProp[$sPropName])){
			Dyhb::E(Dyhb::L('属性：%s不存在。','__DYHB__@Dyhb',null,$sPropName));
		}
		$arrConfig=$oMeta->_arrProp[$sPropName];
		if($arrConfig['readonly']){
			Dyhb::E(Dyhb::L('属性：%s为只读，无法改变值','__DYHB__@Dyhb',null,$sPropName));
		}
		if(!empty($arrConfig['setter'])){
			list($callback,$arrCustomParameters)=$arrConfig['setter'];
			if(!is_array($callback)){
				$callback=array($this,$callback);
				$arrArgs=array($Value,$sPropName,$arrCustomParameters,&$this->_arrProp);
			}else{
				$arrArgs=array($this,$Value,$sPropName,$arrCustomParameters,&$this->_arrProp);
			}
			return call_user_func_array($callback,$arrArgs);
		}
		if(isset($arrCondig['relation']) and $arrConfig['relation']){
			if($arrConfig['relation']==Db::HAS_ONE || $arrConfig['relation']==Db::BELONGS_TO){
				if(!($Value instanceof $arrConfig['relation_class'])){
					Dyhb::E(Dyhb::L('属性：%s的正确类型为：%s,而现在的类型为%s','__DYHB__@Dyhb',null,$sPropName,$arrConfig['relation_class'],gettype($Value)));
				}
				$this->_arrProp[$sPropName]=$Value;
			}else{
				if(is_array($Value)){// 数组，则创建Coll对象
					$this->_arrProp[$sPropName]=Coll::createFromArray($Value,$arrConfig['relation_class']);
				}elseif($Value instanceof Iterator){// 直接是Coll的话，就不用创建
					$this->_arrProp[$sPropName]=$Value;
				}else{
					Dyhb::E('$Value must is array or Iterator');
				}
			}
			$this->_arrChangedProp[$sPropName]=$sPropName;// 设置属性为“脏”状态
		}elseif(array_key_exists($sPropName,$this->_arrProp) && $this->_arrProp[$sPropName]!==$Value){
			$this->_arrProp[$sPropName]=$this->dbFieldtypeCheck_($sPropName,$Value);
			$this->_arrChangedProp[$sPropName]=$sPropName; // 设置属性为“脏”状态
		}
	}
	public function setAutofill($bAutofill=true){
		$this->_bAutofill=$bAutofill;
	}
	public function __isset($sPropName){
		return array_key_exists($sPropName,$this->_arrProp);
	}
	public function __call($sMethod,array $arrArgs){
		if (isset(self::$_arrMeta[$this->_sClassName]->_arrMethods[$sMethod])){// 设置行为插件的方法
			$arrCallback=self::$_arrMeta[$this->_sClassName]->_arrMethods[$sMethod];// 获取回调函数
			foreach ($arrArgs as $sArg){
				array_push($arrCallback[1],$sArg);
			}
			array_unshift($arrCallback[1],$this);
			return call_user_func_array($arrCallback[0],$arrCallback[1]);// 执行回调函数
		}
		$sPrefix=substr($sMethod,0,3);// getXX()和 setXX()方法
		if ($sPrefix=='get'){
			$sPropName=substr($sMethod,3);
			return $this->{$sPropName};
		}elseif($sPrefix=='set'){
			$sPropName=substr($sMethod,3);
			$this->{$sPropName}=reset($arrArgs);
			return null;
		}
		Dyhb::E(Dyhb::L('类%s不存在方法%s','__DYHB__@Dyhb',null,$this->_sClassName,$sMethod));
	}
	public function offsetExists($sPropName){
		return array_key_exists($sPropName,$this->_arrProp);
	}
	public function offsetSet($sPropName,$Value){
		$this->{$sPropName}=$Value;
	}
	public function offsetGet($sPropName){
		return $this->{$sPropName};
	}
	public function offsetUnset($sPropName){
		$this->{$sPropName}=null;
	}
	static function collCallback_(){
		return array('tojson' =>'multiToJson');
	}
	static function multiToJson(array $arrObjects,$nRecursion=99,$sNamesStyle=Db::PROP){
		$arrValue=array();
		while((list(,$oObj)=each($arrObjects))!==null){
			$arrValue[]=$oObj->toArray($nRecursion,$sNamesStyle);
		}
		return json_encode($arrValue);
	}
	protected function method_($sMethod){
		$arrArgs=func_get_args();
		array_shift($arrArgs);
		return $this->__call($sMethod,$arrArgs);
	}
	protected function event_($sEventName){
		$oMeta=self::$_arrMeta[$this->_sClassName];
		if(empty($oMeta->_arrCallback[$sEventName])){
			return;
		}
		// 触发回调事件
		foreach ($oMeta->_arrCallback[$sEventName] as $oCallback){
			array_unshift($oCallback[1],$this);
			call_user_func_array($oCallback[0],$oCallback[1]);
		}
	}
	protected function getPostName($sField){
		$oMeta=self::$_arrMeta[$this->_sClassName];
		$arrMap=$oMeta->_arrPostMapField;
		if($arrMap){
			asort($arrMap);// 对调键和值
			if(isset($arrMap[$sField]) && $arrMap[$sField]!=''){
				return $arrMap[$sField];
			}
		}
		return $sField;
	}
	protected function makePostData(){
		foreach($this->_arrProp as $sField=>$value){
			$sPostName=$this->getPostName($sField);
			if(!isset($this->_arrChangedProp[$sField]) && isset($_POST[$sPostName])){
				$value=trim($_POST[$sPostName]);
				$this->_arrProp[$sField]=$value;
				$this->_arrChangedProp[$sField]=$value; // 设置属性为“脏”状态
			}
		}
	}
	protected function create_($nRecursion=99){
		$oMeta=self::$_arrMeta[$this->_sClassName];
		if($this->_bAutofill===true){
			$this->autoFill_('create');
		}
		$this->beforeCreate_();// 引发 before_create 事件
		$this->event_(self::BEFORE_CREATE);
		$this->beforeCreatePost_();
		if($this->check_('create',true)===false){// 进行create验证
			return false;
		}
		// 特别处理 BELONGS_TO 关联
		foreach($oMeta->_arrBelongstoProp as $sPropName=>$oRelation){
			$sMappingName=$oRelation->_sMappingName;
			$sSourceKey=$oRelation->_sSourceKey; // 关联中，识别来源对象的属性名，如果不设置则以来源对象的主键为准。
			if (empty($this->_arrProp[$sMappingName])){
				if(empty($this->_arrProp[$sSourceKey])){
					if($this->_arrProp[$sSourceKey]===$oMeta->_arrProp[$sSourceKey]['default_value'] && !is_null($oMeta->_arrProp[$sSourceKey]['default_value'])){
						$this->changePropForce($sSourceKey,$oMeta->_arrProp[$sSourceKey]['default_value']);
					}else{
						if(empty($oRelation->_oSourceMeta->_arrProp[$sMappingName]['relation_params']['skip_empty'])){
							Dyhb::E($sMappingName.' BELONGS_TO associated parameter is not present and not set skip_empty also');
						}
					}
				}
			}else{
				$oBelongsto=$this->_arrProp[$sMappingName];
				$this->changePropForce($sSourceKey,$oBelongsto->{$oRelation->_sTargetKey});// sTargetKey关联对象中的目标属性
			}
		}
		// 准备要保存到数据库的数据
		$arrSaveData=array();
		foreach($this->_arrProp as $sPropName=>$sValue){
			if(isset($oMeta->_arrCreateReject[$sPropName]) || (isset($oMeta->_arrProp[$sPropName]) and $oMeta->_arrProp[$sPropName]['virtual'])){
				continue;
			}
			// 过滤NULL值
			if($sValue!==null){
				if(isset($oMeta->_arrPropToField[$sPropName])){
					$arrSaveData[$oMeta->_arrPropToField[$sPropName]]=$sValue;
				}
			}
		}
		// 将名值对保存到数据库
		$arrPk=$oMeta->_oTable->insert($arrSaveData,true);
		if($arrPk===false || $this->isTableError()){
			$this->setErrorMessage($this->getTableErrorMessage());
			return false;
		}
		// 将获得的主键值指定给对象
		foreach ($arrPk as $sFieldName=>$sFieldValue){
			if(isset($oMeta->_arrPropToField[$sFieldName])){
				$this->_arrProp[$oMeta->_arrPropToField[$sFieldName]]=$sFieldValue;
			}
		}
		// 遍历关联的对象，并调用对象的save()方法
		foreach($oMeta->_arrRelation as $sProp => $oRelation){
			if($oRelation->_sType==Db::BELONGS_TO || !array_key_exists($sProp,$this->_arrProp)){
				continue;
			}
			$oRelation->init_();
			$sSourceKeyValue=$this->{$oRelation->_sSourceKey};
			if(strlen($sSourceKeyValue)==0){
				Dyhb::E('The source_key can not be empty');
			}
			$oRelation->onSourceSave($this,$nRecursion-1);// 保存源对象数据
		}
		// 引发after_create事件
		$this->afterCreate_();
		$this->event_(self::AFTER_CREATE);
		$this->afterCreatePost_();
		// 清除所有属性的“脏”状态
		$this->_arrChangedProp=array();
	}
	protected function update_($nRecursion=99){
		$oMeta=self::$_arrMeta[$this->_sClassName];
		if(!empty($this->_arrChangedProp)){
			if($this->_bAutofill===true){// 这里允许update和all
				$this->autofill_('update');
			}
			$this->beforeUpdate_();
			$this->event_(self::BEFORE_UPDATE);
			$this->beforeUpdatePost_();
			if($this->check_('update',true)===false){// 进行update验证
				return false;
			}
			$arrSaveData=array();
			foreach($this->_arrProp as $sPropName=>$value){
				if(isset($oMeta->_arrUpdateReject[$sPropName]) ||
					(isset($oMeta->_arrProp[$sPropName]) && $oMeta->_arrProp[$sPropName]['virtual'] && !isset($oMeta->_arrTableMeta[$sPropName]))
				){
					continue;
				}
				if(isset($this->_arrChangedProp[$sPropName]) && isset($oMeta->_arrPropToField[$sPropName])){
					$arrSaveData[$oMeta->_arrPropToField[$sPropName]]=$value;
				}
			}
			if(!empty($arrSaveData)){
				$arrConditions=array();
				foreach($oMeta->_oTable->getPk()as $sFieldName){
					$sPropName=$oMeta->_arrFieldToProp[$sFieldName];
					unset($arrSaveData[$sFieldName]);
					$arrConditions[$sFieldName]=$this->_arrProp[$sFieldName];
				}
				if(!empty($arrSaveData)){
					$bResult=$oMeta->_oTable->update($arrSaveData,$arrConditions);
					if($bResult===false || $this->isTableError()){
						$this->setErrorMessage($this->getTableErrorMessage());
						return false;
					}
				}
			}
		}
		// 遍历关联的对象，并调用对象的save()方法
		foreach($oMeta->_arrRelation as $sProp=>$oRelation){
			if(!isset($this->_arrProp[$sProp])){
				continue;
			}
			$oRelation->init_();
			$sSourceKeyValue=$this->{$oRelation->_sSourceKey};
			if(strlen($sSourceKeyValue)==0){
				Dyhb::E('The source_key can not be empty');
			}
			$oRelation->onSourceSave($this,$nRecursion-1);
		}
		$this->afterUpdate_();
		$this->event_(self::AFTER_UPDATE);
		$this->afterUpdatePost_();
		$this->_arrChangedProp=array();// 清除所有属性的“脏”状态
	}
	protected function replace_($nRecursion=99){
		$arrChanges=$this->_arrChangedProp;
		if(empty($arrChanges)){
			return;
		}
		try{
			$bResult=$this->create_($nRecursion);// 数据库本身并不支持 replace 操作，所以只能是通过insert操作来模拟
		}catch(Exception $e){
			$this->_arrChangedProp=$arrChanges;
			$this->update_($nRecursion);
		}
	}
	protected function autofill_($sMode=self::MODEL_ALL){
		$oMeta=self::$_arrMeta[$this->_sClassName];
		$sMode=strtoupper($sMode); // 兼容大小写
		$arrFieldToProp =$oMeta->_arrFieldToProp;// 我们要求数据库字段都以小写为准
		// 兼容大小写，字段必须全部为小写&任何时候使用当前时间戳进行填充
		if(array_key_exists(strtolower(self::FIELD_DATELINE),$arrFieldToProp)){
			$this->changePropForce(strtolower(self::FIELD_DATELINE),CURRENT_TIMESTAMP);
		}
		if(strtoupper($sMode)==self::MODEL_CREATE and array_key_exists(strtolower(self::FIELD_CREATE_DATELINE),$arrFieldToProp)){// 创建对象的时候
			$this->changePropForce(strtolower(self::FIELD_CREATE_DATELINE),CURRENT_TIMESTAMP);
		}
		if(strtoupper($sMode)==self::MODEL_UPDATE and array_key_exists(strtolower(self::FIELD_UPDATE_DATELINE),$arrFieldToProp)){// 更新对象的时候
			$this->changePropForce(strtolower(self::FIELD_UPDATE_DATELINE),CURRENT_TIMESTAMP);
		}
		$arrFillProps= $oMeta->_arrAutofill;
		$arrData=$this->_arrProp;
		foreach($arrFillProps as $arrValue){
			$sField=array_key_exists(0,$arrValue)?$arrValue[0]:''; // 字段
			$sContent=array_key_exists(1,$arrValue)?$arrValue[1]:''; // 内容
			$sCondition=array_key_exists(2,$arrValue)?$arrValue[2]:''; // 填充条件
			$sExtend=array_key_exists(3,$arrValue)?$arrValue[3]:''; // 附加规则
			if(strtoupper($sContent)===self::AUTOFILL_DATETIME){// 对$sContent进行特殊处理
				$sContent=date('Y-m-d H:i:s',CURRENT_TIMESTAMP); // DATETIME
			}elseif(strtoupper($sContent)===self::AUTOFILL_TIMESTAMP){
				$sContent=intval(CURRENT_TIMESTAMP); // TIMESTAMP
			}elseif(strtoupper($sContent)===self::AUTOFILL_DATE){
				$sContent=date('Y-m-d',CURRENT_TIMESTAMP); // DATE_
			}elseif(strtoupper($sContent)===self::AUTOFILL_TIME){
				$sContent= date('H:i:s',CURRENT_TIMESTAMP); // TIME_
			}
			// 自动填充类型处理,处理类型为空，那么为all
			if(strtoupper($sMode)==strtoupper($sCondition) ||
				strtoupper($sMode)==self::MODEL_ALL ||
				strtoupper($sCondition)==self::MODEL_ALL || $sCondition==''
			){
				if($sExtend){// 调用附加规则
					switch($sExtend){
						case 'function':// 使用函数进行填充 字段的值作为参数
						case 'callback': // 使用回调方法
							$arrArgs=isset($arrValue[4])?$arrValue[4]:array();// 回调参数
							if(isset($arrData[$sField])){
								array_unshift($arrArgs,$arrData[$sField]);
							}
							if('function'==$sExtend){// funtion回调
								if(function_exists($sContent)){
									$arrData[$sField]=call_user_func_array($sContent,$arrArgs);
								}else{
									Dyhb::E('Function is not exist');
								}
							}else{
								if(is_array($sContent)){
									if(!is_callable($sContent,false)){
										Dyhb::E('Callback is not exist');
									}
									
									$arrData[$sField]=call_user_func_array($sContent,$arrArgs);
								}else{
									$arrData[$sField]=call_user_func_array(array(&$this,$sContent),$arrArgs);
								}
							}
							break;
						case "field":
							$arrData[$sField]=$arrData[$sContent];
							break;
						case "string":
							$arrData[$sField]=strval($sContent);
							break;
					}
				}else{
					$arrData[$sField]=$this->dbFieldtypeCheck_($sField,$sContent);
				}
				$this->_arrChangedProp[$sField]=true;
			}
		}
		$this->_arrProp=$arrData;
		return $this->_arrProp;
	}
	protected function dbFieldtypeCheck_($sProp,$Data){
		$oMeta=self::$_arrMeta[$this->_sClassName];
		if($GLOBALS['_commonConfig_']['DB_FIELDTYPE_CHECK'] && isset($oMeta->_arrProp[$sProp]['ptype'])){
			$Data=self::typed_($Data,$oMeta->_arrProp[$sProp]['ptype']);
		}
		return $Data;
	}
	public function check_($sMode){
		$this->beforeCheck_();
		$this->event_(self::BEFORE_CHECK);
		$this->beforeCheckPost_();
		$oMeta=self::$_arrMeta[$this->_sClassName];
		if($sMode=='create'){// 如果是创建验证，触发相关前置事件
			$this->beforeCheckOnCreate_();
			$this->event_(self::BEFORE_CHECK_ON_CREATE);
			$this->beforeCheckOnCreatePost_();
			$arrCheckProps=array_keys($oMeta->_arrPropToField);
			foreach($oMeta->_arrIdName as $sIdname){
				unset($arrCheckProps[$sIdname]);
			}
			$arrCheckProps=array_flip($arrCheckProps);
		}elseif($sMode=='update'){// 如果是更新，触发相关前置事件
			$this->beforeCheckOnUpdate_();
			$this->event_(self::BEFORE_CHECK_ON_UPDATE);
			$this->beforeCheckOnUpdatePost_();
			$arrCheckProps=array_keys($oMeta->_arrPropToField);
			foreach($oMeta->_arrIdName as $sIdname){
				unset($arrCheckProps[$sIdname]);
			}
			$arrCheckProps=array_flip($arrCheckProps);
		}
		$arrError=$oMeta->check($this->_arrProp,$arrCheckProps,$sMode);
		if(!empty($arrError)){
			$sErrorMessage='<ul class="DyhbModelList">';
			foreach($arrError as $sField=>$arrValue){
				foreach($arrValue as $sK=>$sV){
					$sErrorMessage.='<li>'.$sV.'</li>';
				}
			}
			$sErrorMessage.='</ul>';
			$this->setErrorMessage($sErrorMessage);
			return false;
		}
		if($sMode=='create'){// 触发了创建时的事件
			$this->afterCheckOnCreate_();
			$this->event_(self::AFTER_CHECK_ON_CREATE);
			$this->afterCheckOnCreatePost_();
		}elseif($sMode=='update'){// 触发了更新的事件
			$this->afterCheckOnUpdate_();
			$this->event_(self::AFTER_CHECK_ON_UPDATE);
			$this->afterCheckOnUpdatePost_();
		}
		// 最后触发验证后的事件
		$this->afterCheck_();
		$this->event_(self::AFTER_CHECK);
		$this->afterCheckPost_();
	}
	public function getClassName(){
		return $this->_sClassName;
	}
	public function getMeta(){
		return self::$_arrMeta[$this->_sClassName];
	}
	public function getTableEnter(){
		return self::$_arrMeta[$this->_sClassName]->_oTable;
	}
	public function getDb(){
		return self::$_arrMeta[$this->_sClassName]->_oTable->getDb();
	}
	public function getTablePrefix(){
		return self::$_arrMeta[$this->_sClassName]->_oTable->getDb()->getConnect()->getTablePrefix();
	}
	public function hasProp($sPropName){
		return isset(self::$_arrMeta[$this->_sClassName]->_arrProp[$sPropName]);
	}
	public function reload(){
		if(self::$_arrMeta[$this->_sClassName]->_nIdNameCount>1){
			$where=$this->id();// 复合主键
		}else{
			$where=array(reset(self::$_arrMeta[$this->_sClassName]->_arrIdName)=>$this->id());// 单一主键
		}
		$arrRow=self::$_arrMeta[$this->_sClassName]->find($where)->asArray()->recursion(0)->query();
		$this->changeProps($arrRow,Db::FIELD,null,true);
	}
	public function destroy(){
		$id=$this->id(false); // FIXED! 不使用缓存
		if(empty($id)){
			Dyhb::E('Database primary key does not exist');
		}
		// 引发 before_destroy 事件
		$this->beforeDestroy_();
		$this->event_(self::BEFORE_DESTROY);
		$this->beforeDestroyPost_();
		$oMeta=self::$_arrMeta[$this->_sClassName];
		foreach($oMeta->_arrRelation as $oRelation){
			$oRelation->onSourceDestroy($this);
		}
		// 确定删除当前对象的条件
		if($oMeta->_nIdNameCount>1){
			$where=$id;
		}else{
			$where=array(reset($oMeta->_arrIdName)=>$id);
		}
		// 从数据库中删除当前对象
		$bResult=$oMeta->_oTable->delete($where);
		if($bResult===false || $this->isTableError()){
			$this->setErrorMessage($this->getTableErrorMessage());
			return false;
		}
		// 引发 after_destroy 事件
		$this->afterDestroy_();
		$this->event_(self::AFTER_DESTROY);
		$this->afterDestroyPost_();
	}
	static protected function typed_($Value,$sPtype){
		switch($sPtype){
			case 'int1':
			case 'int2':
			case 'int3':
			case 'int4':
			case 'timestamp':
				return intval($Value);
			case 'float':
			case 'double':
			case 'dec':
				 return doubleval($Value);
			case 'bool':
				return (bool)$Value;
			case 'date':
			case 'datetime':
				return empty($Value)?null:$Value;
		}
		return $Value;
	}
	public function changes(){
		return $this->_arrChangedProp;
	}
	public function changed($sPropsName=null){
		if(is_null($sPropsName)){// null 判读是否存在属性
			return !empty($this->_arrChangedProp);
		}
		$arrPropsName=Dyhb::normalize($sPropsName);
		foreach($arrPropsName as $sPropName){
			if(isset($this->_arrChangedProp[$sPropName])){
				return true;
			}
		}
		return false;
	}
	public function willChanged($sPropsName){
		$arrPropsName=Dyhb::normalize($sPropsName);
		foreach($arrPropsName as $sPropsName){
			if(!isset(self::$_arrMeta[$this->_sClassName]->_arrProp[$sPropsName])){
				continue;
			}
			$this->_changedProp[$sPropsName]=$sPropsName;
		}
		return $this;
	}
	public function cleanChanges($Props=null){
		if($Props){
			$arrProps=Dyhb::normalize($Props);
			foreach($arrProps as $sProp){
				unset($this->_arrChangedProp[$sProp]);
			}
		}else{
			$this->_arrChangedProp=array();
		}
	}
	public function toArray($nRecursion=99,$sNamesStyle=Db::PROP){
		$arrData=array();
		$oMeta=self::$_arrMeta[$this->_sClassName];
		foreach($oMeta->_arrProp as $sPropName=>$arrConfig){
			if($sNamesStyle==Db::PROP){
				$sName=$sPropName;
			}else{
				$sName=$oMeta->_arrPropToField[$sPropName];
			}
			if($arrConfig['relation']){
				if($nRecursion>0 && isset($this->_arrProp[$sPropName])){
					$arrData[$sName]=$this->{$sPropName}->toArray($nRecursion-1,$sNamesStyle);
				}
			}elseif($arrConfig['virtual'] && empty($arrConfig['getter'])){
				continue;
			}else{
				$arrData[$sName]=$this->{$sPropName};
			}
		}
		return $arrData;
	}
	public function toJson($nRecursion=99,$sNamesStyle=Db::PROP){
		return json_encode($this->toArray($nRecursion,$sNamesStyle));
	}
	protected function isTableError(){
		$oMeta=self::$_arrMeta[$this->_sClassName];
		return $oMeta->_oTable->isError();
	}
	protected function getTableErrorMessage(){
		$oMeta=self::$_arrMeta[$this->_sClassName];
		return $oMeta->_oTable->getErrorMessage();
	}
	public function __clone(){
		 foreach(self::$_arrMeta[$this->_sClassName]->_arrIdName as $sName){
			$this->_arrProp[$sName]=self::$_arrMeta[$this->_sClassName]->_oTableMeta[$sName]['default'];
		}
		$this->_id=false; // FIXED! 清除缓存
	}
	static public function getModelByGlobalName($sName){
		return isset(self::$_arrGlobalModels[$sName])?self::$_arrGlobalModels[$sName]:null;
	}
	protected function setIsError($bIsError=false){
		$bOldValue=$this->_bIsError;
		$this->_bIsError=$bIsError;
		return $bOldValue;
	}
	protected function setErrorMessage($sErrorMessage=''){
		$this->setIsError(true);
		$sOldValue=$this->_sErrorMessage;
		$this->_sErrorMessage=$sErrorMessage;
		return $sOldValue;
	}
	public function isError(){
		return $this->_bIsError;
	}
	public function getErrorMessage(){
		return $this->_sErrorMessage;
	}
	protected function beforeCheck_(){}
	protected function beforeCheckPost_(){}
	protected function beforeCheckOnCreate_(){}
	protected function beforeCheckOnCreatePost_(){}
	protected function afterCheckOnCreate_(){}
	protected function afterCheckOnCreatePost_(){}
	protected function beforeCheckOnUpdate_(){}
	protected function beforeCheckOnUpdatePost_(){}
	protected function afterCheckOnUpdate_(){}
	protected function afterCheckOnUpdatePost_(){}
	protected function afterCheck_(){}
	protected function afterCheckPost_(){}
	protected function afterCreate_(){}
	protected function afterCreatePost_(){}
	protected function beforeUpdate_(){}
	protected function beforeUpdatePost_(){}
	protected function afterUpdate_(){}
	protected function afterUpdatePost_(){}
	protected function beforeDestroy_(){}
	protected function beforeDestroyPost_(){}
	protected function afterDestroy_(){}
	protected function afterDestroyPost_(){}
	protected function beforeCreate_(){}
	protected function beforeCreatePost_(){}
	protected function afterSave_(){}
	protected function afterSavePost_(){}
	protected function beforeSave_(){}
	protected function beforeSavePost_(){}
	protected function afterInit_(){ }
	protected function afterInitPost_(){ }
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   模型回调($)*/
interface IModelCallback{
	const BEFORE_FIND='beforeFind';
	const AFTER_FIND='afterFind';
	const AFTER_INIT='afterInit';
	const BEFORE_SAVE='beforeSave';
	const AFTER_SAVE='afterSave';
	const BEFORE_CREATE='beforeCreate';
	const AFTER_CREATE='afterCreate';
	const BEFORE_UPDATE='beforeUpdate';
	const AFTER_UPDATE='afterUpdate';
	const BEFORE_CHECK='beforeCheck';
	const AFTER_CHECK='afterCheck';
	const BEFORE_CHECK_ON_CREATE='beforeCheckOnCreate';
	const AFTER_CHECK_ON_CREATE='afterCheckOnCreate';
	const BEFORE_CHECK_ON_UPDATE='beforeCheckOnUpdate';
	const AFTER_CHECK_ON_UPDATE='afterCheckOnUpdate';
	const BEFORE_DESTROY='beforeDestroy';
	const AFTER_DESTROY='afterDestroy';
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   ModelMeta元模式($)*/
class ModelMeta{
	public $_arrMethods=array();
	public $_arrStaticMethods=array();
	public $_arrDefaultProp=array();
	public $_arrCallback=array();
	public $_arrAttrAccessible=array();
	public $_arrAttrProtected=array();
	public $_arrPropToField=array();
	public $_arrFieldToProp=array();
	public $_arrProp=array();
	protected static $_arrMeta=array();
	public $_arrBelongstoProp=array();
	public $_arrRelation=array();
	public $_sInheritTypeField;
	public $_arrIdName;
	public $_nIdNameCount;
	public $_sSourceKey;
	public $_sInheritBaseClass;
	public $_oTable;
	public $_arrTableMeta;
	public $_arrCheck=array();
	public $_arrCreateReject=array();
	public $_arrUpdateReject=array();
	public $_arrAutofill=array();
	public $_arrPostMapField=array();
	protected $_arrBehaviors=array();
	protected static $_arrRelationTypes=array(
		Db::HAS_ONE,
		Db::HAS_MANY,
		Db::BELONGS_TO,
		Db::MANY_TO_MANY
	);
	protected static $_arrCheckOptions=array(
		'allow_null'=>false,
		'check_all_rules'=>false
	);
	protected $_sLastErrorMessage='';
	protected $_bIsError=false;
	protected static $_arrMapConfigKeys=array(
		'mapping_name'=>'_sMappingName',
		'source_key'=>'_sSourceKey',
		'target_key'=>'_sTargetKey',
		'on_find'=>'_onFind',
		'on_find_where'=>'_onFindWhere',
		'on_find_order'=>'_sOnFindOrder',
		'on_find_keys'=>'_onFindKeys',
		'on_delete'=>'_onDelete',
		'on_delete_set_value'=>'_onDeleteSetValue',
		'on_save'=>'_sOnSave',
		'mid_source_key'=>'_sMidSourceKey',
		'mid_target_key'=>'_sMidTargetKey',
		'mid_on_find_keys'=>'_midOnFindKeys',
		'mid_mapping_to'=>'_sMidMappingTo',
		'mid_class'=>'_sMidClass',
		'enabled'=>'_bEnabled',
		'target_class'=>'_sTargetClass',
	);
	protected function __construct($sClass){
		$this->init_($sClass);
	}
	static public  function instance($sClass){
		if (!isset(self::$_arrMeta[$sClass])){
			self::$_arrMeta[$sClass]=new self($sClass);
			self::$_arrMeta[$sClass]->initInstance_();
		}
		return self::$_arrMeta[$sClass];
	}
	public function find(){
		return $this->findByArgs(func_get_args());
	}
	public function findByArgs(array $arrArgs=array()){
		$oSelect=new DbSelect($this->_oTable->getConnect());
		$oSelect->asColl()->from($this->_oTable)->asObj($this->_sClassName);
		$nC=count($arrArgs);
		if($nC>0){
			if($nC==1 && is_int($arrArgs[0]) && $this->_nIdNameCount==1){
				$oSelect->where(array(reset($this->_arrIdName)=>$arrArgs[0]));
			}else{
				call_user_func_array(array($oSelect,'where'),$arrArgs);
			}
		}
		if($this->_sInheritBaseClass && $this->_sInheritTypeField){
			$oSelect->where(array($this->_sInheritTypeField=>$this->_sClassName));// 如果是来自某个继承类的查询，则限定只能查询该类型的对象
		}
		return $oSelect;
	}
	public function updateWhere(array $arrAttribs){
		$arrArgs=func_get_args();
		array_shift($arrArgs);
		$arrObjs=$this->findByArgs($arrArgs)->all()->query();
		foreach($arrObjs as $oObj){
			$oObj->changeProps($arrAttribs);
			$oObj->save(0,'update');
			unset($oObj);
		}
	}
	public function updateDbWhere(){
		$arrArgs=func_get_args();
		call_user_func_array(array($this->_oTable,'update'),$arrArgs);
	}
	public function destroyWhere(){
		$arrObjs=$this->findByArgs(func_get_args())->all()->query();
		$nC=count($arrObjs);
		foreach($arrObjs as $oObj){
			$oObj->destroy();
			unset($oObj);
		}
		return $nC;
	}
	public function deleteWhere(){
		$arrArgs=func_get_args();
		call_user_func_array(array($this->_oTable,'delete'),$arrArgs);
		if($this->_oTable->isError()){
			$this->setErrorMessage($this->getErrorMessage());
			return false;
		}
	}
	public function newObj(array $Data=null){
		return new $this->_sClassName($Data);
	}
	public function addRelation($sPropName,$sRelationType,array $arrConfig){
		if(!in_array($sRelationType,array(Db::HAS_ONE,Db::HAS_MANY,Db::BELONGS_TO,Db::MANY_TO_MANY))){
			Dyhb::E('Relation type is error');
		}
		switch($sRelationType){
			case Db::HAS_ONE:
			case Db::HAS_MANY:
				if(empty($arrConfig['target_key'])){
					$arrConfig['target_key']=strtolower(substr($this->_sClassName,0,-5)).'_id';
				}
				break;
			case Db::BELONGS_TO:
				if(empty($arrConfig['source_key'])){
					$arrConfig['source_key']=strtolower($arrConfig['relation_class']).'_id';
				}
				break;
			case Db::MANY_TO_MANY:
				if(empty($arrConfig['mid_source_key'])){
					$arrConfig['mid_source_key']=strtolower($this->_sClassName).'_id';
				}
				if(empty($arrConfig['mid_target_key'])){
					$arrConfig['mid_target_key']=strtolower($arrConfig['relation_class']).'_id';
				}
		}
		$arrRelation=$arrConfig['relation_params'];
		$arrRelation['mapping_name']=$sPropName;
		$arrRelation['target_class']=$arrConfig['relation_class'];
		if(isset($arrRelation[$sRelationType])){
			unset($arrRelation[$sRelationType]);
		}
		$arrRelationMap=array();// 建立传递过来的数据映射
		foreach($arrRelation as $sKey=>$value){
			if(isset(self::$_arrMapConfigKeys[$sKey])){
				$arrRelationMap[self::$_arrMapConfigKeys[$sKey]]=$value;
			}
		}
		// 创建一个关联对象
		$oRelation=ModelRelation::create($sRelationType,$arrRelationMap,$this);
		$oRelation->regCallback($arrRelationMap);
		$this->_arrRelation[$sPropName]=$oRelation;
		if($oRelation->_sType==Db::BELONGS_TO){
			$oRelation->init_();
			$this->_arrBelongstoProp[$oRelation->_sSourceKey]=$oRelation;
		}
		return $oRelation;
	}
	public function removeRelation($sPropName){
		unset($this->_arrProp[$sPropName]);
		unset($this->_arrRelations[$sPropName]);
		return $this;
	}
	public function relatedObj(Model $oObj,$sPropName){
		if(!class_exists($this->_arrProp[$sPropName]['relation_class'])){
			Dyhb::E('The relation_class is not exist');
		}
		$oTargetMeta=self::instance($this->_arrProp[$sPropName]['relation_class']);
		$oRelation=$this->relation($sPropName)->init_();
		$sourceKeyValue=$oObj->{$oRelation->_sSourceKey};
		if(empty($sourceKeyValue)){
			if($oRelation->_bOneToOne){
				return $oTargetMeta->newObj();
			}else{
				return new ModelRelationColl($oTargetMeta->_sClassName);
			}
		}
		switch($oRelation->_sType){
			case Db::HAS_ONE:
			case Db::HAS_MANY:
				$oSelect=$oTargetMeta->find(array($oRelation->_sTargetKey=>$sourceKeyValue));
				break;
			case Db::BELONGS_TO:
				$oSelect=$oTargetMeta->find(array($oRelation->_sTargetKey=>$oObj->{$oRelation->_sTargetKey}));
				break;
			case Db::MANY_TO_MANY:
				$oRelation->_oMidTable->init();
				$oSelect=$oTargetMeta->find("[{$oRelation->_sTargetKey}]=[m.{$oRelation->_sMidTargetKey}]")->joinInner(array('m'=>$oRelation->_oMidTable), null,"[{$oRelation->_sMidSourceKey}]=?", $sourceKeyValue);
				break;
			default:
				Dyhb::E(Dyhb::L('无效的关联类型 %s.','__DYHB__@Dyhb',null,$oRelation->_sType));
		}
		if(!empty($oRelation->_onFindWhere)){// where条件
			call_user_func_array(array($oSelect,'where'),$oRelation->_onFindWhere);
		}
		if(!empty($oRelation->_sOnFindOrder)){// order条件
			$oSelect->order($oRelation->_sOnFindOrder);
		}
		if($oRelation->_onFind==='all' || $oRelation->_onFind===true){// 是否全部记录
			$oSelect->all();
		}elseif(is_int($oRelation->_onFind)){// 一定条件记录
			$oSelect->limit(0,$oRelation->_onFind);
		}elseif(is_array($oRelation->_onFind)){// 范围
			$oSelect->limit($oRelation->_onFind[0],$oRelation->_onFind[1]);
		}
		if($oRelation->_bOneToOne){// 一对一
			$arrObjects=$oSelect->query();
			if(count($arrObjects)){
				return(is_object($arrObjects))?$arrObjects->first():reset($arrObjects);
			}else{
				return $oTargetMeta->newObj();
			}
		}else{// 一对多
			return $oSelect->asColl()->query();
		}
	}
	public function relation($sPropName){
		if(!isset($this->_arrRelation[$sPropName])){
			Dyhb::E('The relation attribute name is not exist');
		}
		return $this->_arrRelation[$sPropName];
	}
	public function addProp($sPropName, array $arrConfig){
		if(isset($this->_arrProp[ $sPropName ])){
			Dyhb::E(Dyhb::L('尝试添加的属性 %s 已经存在。','__DYHB__@Dyhb',null,$sPropName));
		}
		$arrConfig=array_change_key_case($arrConfig,CASE_LOWER);
		$arrParams=array('relation' =>false);
		$arrParams['readonly']=isset($arrConfig['readonly'])?(bool)$arrConfig['readonly']:false;
		if(!empty($arrConfig['field_name'])){// 确定属性和字段名之间的映射关系
			$sFieldName=$arrConfig['field_name'];
		}else{
			$sFieldName=isset($this->_arrTableMeta[$sPropName])?$this->_arrTableMeta[$sPropName]['name']:$sPropName;
		}
		$this->_arrPropToField[$sPropName]=$sFieldName;
		$this->_arrFieldToProp[$sFieldName]=$sPropName;
		$sMetaKeyName=strtolower($sFieldName);// 根据数据表的元信息确定属性是否是虚拟属性
		if(!empty($this->_arrTableMeta[$sMetaKeyName])){
			$arrParams['virtual']=false;// 如果是非虚拟属性，则根据数据表的元信息设置属性的基本验证策略
			$arrFieldMeta=$this->_arrTableMeta[$sMetaKeyName];
			$arrParams['default_value']=$arrFieldMeta['default'];
			$arrParams['ptype']=$arrFieldMeta['ptype'];
		}else{
			$arrParams['virtual']=true;
			$arrParams['default_value']=null;
			$arrParams['ptype']='varchar';
		}
		foreach(self::$_arrRelationTypes as $sType){// 处理对象聚合
			if(empty($arrConfig[$sType])){
				continue;
			}
			$arrParams['relation']=$sType;
			$arrParams['relation_class']=$arrConfig[$sType];
			$arrParams['relation_params']=$arrConfig;
			$this->_arrRelation[$sPropName]=$arrParams;
		}
		if(!$arrParams['virtual'] || $arrParams['relation']){// 设置属性信息
			$this->_arrDefaultProp[$sPropName]=$arrParams['default_value'];
		}
		$this->_arrProp[$sPropName]=$arrParams;
		if(!empty($arrConfig['setter'])){
			$arrSetterParams=!empty($arrConfig['setter_params'])?(array)$arrConfig['setter_params']:array();// 设置 getter 和 setter
			if(is_array($arrConfig['setter'])){
				$this->setPropSetter($sPropName, $arrConfig['setter'], $arrSetterParams);
			}else{
				if(strpos($arrConfig['setter'],'::')){
					$arrConfig['setter']=explode('::',$arrConfig['setter']);
				}
				$this->setPropSetter($sPropName, $arrConfig['setter'], $arrSetterParams);
			}
		}
		if(!empty($arrConfig['getter'])){
			$arrGetterParams=!empty($arrConfig['getter_params'])?(array)$arrConfig['getter_params']:array();
			if(is_array($arrConfig['getter'])){
				$this->setPropGetter($sPropName, $arrConfig['getter'], $arrGetterParams);
			}else{
				if(strpos($arrConfig['getter'],'::')){
					$arrConfig['getter']=explode('::',$arrConfig['getter']);
				}
				$this->setPropGetter($sPropName,$arrConfig['getter'],$arrGetterParams);
			}
		}
		return $this;
	}
	public function hasBindBehavior($sName){
		return isset($this->_arrBehaviors[$sName])?true:false;
	}
	public function bindBehaviors($Behaviors,array $arrConfig=null){
		$arrBehaviors=Dyhb::normalize($Behaviors);
		if(!is_array($arrConfig)){
			$arrConfig=array();
		}else{
			$arrConfig=array_change_key_case($arrConfig,CASE_LOWER);
		}
		foreach($arrBehaviors as $sName){
			$sName=strtolower($sName);
			if(isset($this->_arrBehaviors[$sName])){// 已经绑定过的插件不再绑定
				continue;
			}
			$sClass='ModelBehavior'.ucfirst($sName);// 载入插件
			$arrSettings=(!empty($arrConfig[$sName]))?$arrConfig[$sName]:array();// 构造行为插件
			$this->_arrBehaviors[$sName]=new $sClass($this, $arrSettings);
		}
		return $this;
	}
	public function unbindBehaviors($Behaviors){
		$arrBehaviors=Dyhb::normalize($Behaviors);
		foreach($arrBehaviors as $sName){
			$sName=strtolower($sName);
			if(!isset($this->_arrBehaviors[$sName])){
				continue;
			}
			$this->_arrBehaviors[$sName]->unbind();
			unset($this->_arrBehaviors[$sName]);
		}
		return $this;
	}
	public function addDynamicMethod($sMethodName,$callback,array $arrCustomParameters=array()){
		if(!empty($this->_arrMethods[$sMethodName])){
			Dyhb::E(Dyhb::L('指定的动态方法名 %s 已经存在于 %s 对象中.','__DYHB__@Dyhb',null,$sMethodName,$this->_sClassName));
		}
		$this->_arrMethods[$sMethodName]=array($callback,$arrCustomParameters);
		return $this;
	}
	public function removeDynamicMethod($sMethodName){
		unset($this->_arrMethods[$sMethodName]);
		return $this;
	}
	public function addStaticMethod($sMethodName,$callback,array $arrCustomParameters=array()){
		if(!empty($this->_arrStaticMethods[$sMethodName])){
			Dyhb::E(Dyhb::L('指定的静态方法名 %s 已经存在于 %s 对象中.','__DYHB__@Dyhb',null,$sMethodName,$this->_sClassName));
		}
		$this->_arrStaticMethods[$sMethodName]=array($callback,$arrCustomParameters);
		return $this;
	}
	public function removeStaticMethod($sMethodName){
		unset($this->_arrStaticMethods[$sMethodName]);
		return $this;
	}
	public function setPropSetter($sPropName,$hCallback,array $arrCustomParameters=array()){
		if(isset($this->_arrProp[$sPropName])){
			$this->_arrProp[$sPropName]['setter']=array($hCallback,$arrCustomParameters);
		}else{
			$this->addProp($sPropName,array('setter'=>$hCallback,'setter_params'=>$arrCustomParameters));
		}
		return $this;
	}
	public function unsetPropSetter($sPropName){
		if(isset($this->_arrProp[$sPropName])){
			unset($this->_arrProp[$sPropName]['setter']);
		}
		return $this;
	}
	public function setPropGetter($sName,$hCallback,array $arrCustomParameters=array()){
		if(isset($this->_arrProp[$sName])){
			$this->_arrProp[$sName]['getter']=array($hCallback,$arrCustomParameters);
		}else{
			$this->addProp($sName,array('getter'=>$hCallback,'getter_params'=>$arrCustomParameters));
		}
	}
	public function unsetPropGetter($sPropName){
		if(isset($this->_arrProp[$sPropName])){
			unset($this->_arrProp[$sPropName]['getter']);
		}
		return $this;
	}
	public function addEventHandler($sEventType,$Callback,array $arrCustomParameters=array()){
		$this->_arrCallback[$sEventType][]=array($Callback,$arrCustomParameters);
		return $this;
	}
	public function removeEventHandler($sEventType,$Callback){
		if (empty($this->_arrCallback[$sEventType])){
			return $this;
		}
		foreach($this->_arrCallback[$sEventType] as $offset=>$arrValue){
			if($arrValue[0]==$Callback){
				unset($this->_arrCallback[$sEventType][$offset]);
				return $this;
			}
		}
		return $this;
	}
	private function init_($sClass){
		$this->_sClassName=$sClass;
		$arrRef=(array)call_user_func(array($sClass,'init__'));
		if(!empty($arrRef['inherit'])){
			$this->_sInheritBaseClass=$arrRef['inherit'];
			$arrBaseRef=(array)call_user_func(array($this->_sInheritBaseClass,'init__'));// 继承类的 init__()方法只需要指定与父类不同的内容
			$arrRef=array_merge_recursive($arrBaseRef,$arrRef);
		}
		$this->_sInheritTypeField=!empty($arrRef['inherit_type_field'])?$arrRef['inherit_type_field']:null;// 被继承的类
		$arrTableConfig=!empty($arrRef['table_config'])?(array)$arrRef['table_config']:array();// 设置表数据入口对象
		if (!empty($arrRef['table_class'])){
			$this->_oTable=$this->tableByClass_($arrRef['table_class'],$arrTableConfig);
		}else{
			$this->_oTable=$this->tableByName_($arrRef['table_name'],$arrTableConfig);
		}
		$this->_arrTableMeta=$this->_oTable->columns();
		if(empty($arrRef['props']) || !is_array($arrRef['props'])){// 根据字段定义确定字段属性
			$arrRef['props']=array();
		}
		foreach($arrRef['props'] as $sPropName=>$arrConfig){
			$this->addProp($sPropName,$arrConfig);
		}
		foreach($this->_arrTableMeta as $sPropName=>$field){// 将没有指定的字段也设置为对象属性
			if(isset($this->_arrPropToField[$sPropName]))continue;
			$this->addProp($sPropName,$field);
		}
		// 设置其他选项
		if(!empty($arrRef['create_reject'])){
			$this->_arrCreateReject=array_flip(Dyhb::normalize($arrRef['create_reject']));
		}
		if(!empty($arrRef['update_reject'])){
			$this->_arrUpdateReject=array_flip(Dyhb::normalize($arrRef['update_reject']));
		}
		if(!empty($arrRef['post_map_field'])){
			$this->_arrPostMapField=$arrRef['postMapField'];
		}
		if(!empty($arrRef['autofill']) && is_array($arrRef['autofill'])){
			$this->_arrAutofill=$arrRef['autofill'];
		}
		if(!empty($arrRef['attr_accessible'])){
			$this->_arrAttrAccessible=array_flip(Dyhb::normalize($arrRef['attr_accessible']));
		}
		if(!empty($arrRef['attr_protected'])){
			$this->_arrAttrProtected=array_flip(Dyhb::normalize($arrRef['attr_protected']));
		}
		// 准备验证规则
		if(empty($arrRef['check']) || ! is_array($arrRef['check'])){
			$arrRef['check']=array();
		}
		$this->_arrCheck=$this->prepareCheckRules_($arrRef['check']);
		$arrPk=$this->_oTable->getPk();// 设置对象ID属性名
		$this->_arrIdName=array();
		foreach($this->_oTable->getPk() as $sPk){
			$sPn=$this->_arrFieldToProp[$sPk];
			$this->_arrIdName[$sPn]=$sPn;
		}
		$this->_nIdNameCount=count($this->_arrIdName);
		if(isset($arrRef['behaviors'])){// 绑定行为插件
			$arrCconfig=isset($arrRef['behaviors_settings'])?$arrRef['behaviors_settings']:array();
			$this->bindBehaviors($arrRef['behaviors'],$arrCconfig);
		}
	}
	protected function prepareCheckRules_($arrPolicies,array $arrRef=array(),$bSetPolicy=true){
		$arrCheck=$this->_arrCheck;
		foreach($arrPolicies as $sPropName=>$arrPolicie){
			if(!is_array($arrPolicie)){
				continue;
			}
			$arrCheck[$sPropName]=array('check'=>self::$_arrCheckOptions,'rules'=>array());
			if(isset($this->_arrPropsToField[$sPropName])){
				$sFn=$this->_arrPropsToField[$sPropName];
				if(isset($this->_arrTableMeta[ $sFn ])){
					$arrCheck[$sPropName]['check']['allow_null']=!$this->_arrTableMeta[$sFn]['not_null'];
				}
			}
			if(!$bSetPolicy){
				unset($arrCheck[$sPropName]['check']);
			}
			foreach($arrPolicie as $sOption=>$rule){
				if(isset($arrCheck[$sPropName]['policy'][$sOption])){
					$arrCheck[$sPropName]['policy'][$sOption]=$rule;
				}elseif($sOption==='on_create' || $sOption==='on_update'){// 解析 on_create 和 on_update 规则
					$rule=array($sOption=>(array)$rule);
					$arrRet=$this->prepareCheckRules_($rule, $arrCheck[$sPropName]['rules'],false);
					$arrCheck[$sPropName][$sOption]=$arrRet[$sOption];
				}elseif($sOption==='include'){
					$arrInclude=Dyhb::normalize($rule);
					foreach($arrInclude as $sRuleName){
						if(isset($arrRef[$sRuleName])){
							$arrCheck[$sPropName]['rules'][$sRuleName]=$arrRef[$sRuleName];
						}
					}
				}elseif(is_array($rule)){
					if(is_string($sOption)){
						$sRuleName=$sOption;
					}else{
						$sRuleName=$rule[0];
						if(is_array($sRuleName)){
							$sRuleName=$sRuleName[count($sRuleName)-1];
						}
						if(isset($arrCheck[$sPropName]['rules'][$sRuleName])){
							$sRuleName.='_'.($sOption+1);
						}
					}
					$arrCheck[$sPropName]['rules'][$sRuleName]=$rule;
				}else{
					Dyhb::E(Dyhb::L('指定了无效的验证规则 %s.','__DYHB__@Dyhb',null,$sOption.' - '.$rule));
				}
			}
		}
		return $arrCheck;
	}
	protected function tableByName_($sTableName,array $arrTableConfig=array()){
		$arrTableConfig=$this->parseDsn($arrTableConfig,$sTableName);
		$oTable=Dyhb::instance('DbTableEnter',$arrTableConfig);
		return $oTable;
	}
	protected function tableByClass_($sTableClass,array $arrTableConfig=array()){
		$arrTableConfig=$this->parseDsn($arrTableConfig,$sTableClass,true);
		$oTable=Dyhb::instance($sTableClass,$arrTableConfig);
		return $oTable;
	}
	protected function parseDsn($arrTableConfig, $sTableName,$bByClass=false){
		if (is_array($arrTableConfig) && G::oneImensionArray($arrTableConfig)){
			if($bByClass===false){$arrTableConfig['table_name']=$sTableName;}
			$arrDsn[]=$arrTableConfig;
		}else{
			if($bByClass===false){
				foreach($arrTableConfig as $nKey=>$arrValue){
					if($bByClass===false){$arrTableConfig[$nKey]['table_name']=$sTableName;}
				}
			}
			$arrDsn=$arrTableConfig;
		}
		return $arrDsn;
	}
	private function initInstance_(){
		foreach(array_keys($this->_arrRelation) as $sPropName){
			$arrConfig=$this->_arrRelation[$sPropName];
			if(is_array($arrConfig)){
				$this->addRelation($sPropName, $arrConfig['relation'],$arrConfig);
			}
		}
	}
	public function changes(){
		return $this->_arrChangedProps;
	}
	public function check(array $arrData,$arrProps=null,$sMode='all'){
		if(!is_null($arrProps)){
			$arrProps=Dyhb::normalize($arrProps,',',true);// 这里不过滤空值
		}else{
			$arrProps=$this->_arrPropToField;
		}
		$arrError=array();
		if(empty($sMode)){// 初始化模式
			$sMode='';
		}
		$sMode='on_'.strtolower($sMode);
		foreach($this->_arrCheck as $sProp=>$arrPolicy){
			if(!isset($arrProps[$sProp])){
				continue;
			}
			if(!isset($arrData[$sProp])){
				$arrData[$sProp]=null;
			}
			if(isset($this->_arrBelongstoProp[$sProp]) && empty($arrPolicy['rules'])){
				continue;
			}
			if(isset($arrPolicy[$sMode])){
				$arrPolicy=$arrPolicy[$sMode];
			}
			if(is_null($arrData[$sProp])){
				if(isset($this->_autofill[$sProp])){// 对于 null 数据，如果指定了自动填充，则跳过对该数据的验证
					continue 2;
				}
				if (isset($arrPolicy['policy'])&& !$arrPolicy['policy']['allow_null']){// allow_null 为 false 时，如果数据为 null，则视为验证失败
					$arrError[$sProp]['not_null']='not null';
				}elseif(empty($arrPolicy['rules'])){
					continue;
				}
			}
			foreach($arrPolicy['rules'] as $sIndex => $arrRule){// 验证规则
				$sExtend='';// 附加规则
				if(array_key_exists('extend',$arrRule)){
					$sExtend=strtolower($arrRule['extend']);
					unset($arrRule['extend']);
				}
				$sCondition='';// 验证条件
				if(array_key_exists('condition',$arrRule)){
	 				$sCondition=strtolower($arrRule['condition']);
	 				unset($arrRule['condition']);
				}
				$sTime='';// 验证时间
				if(array_key_exists('time',$arrRule)){
	 				$sTime=strtolower($arrRule['time']);
	 				unset($arrRule['time']);
				}
				$sCheck=array_shift($arrRule);// 验证规则
				$sMsg=array_pop($arrRule);// 验证消息
				array_unshift($arrRule,$arrData[$sProp]);
				$arrCheckInfo=array('field'=>$sProp,'extend'=>$sExtend,'message'=>$sMsg,'check'=>$sCheck,'rule'=>$arrRule);// 组装成验证信息
				if($sTime!='' and $sTime!='all' and $sMode!='on_'.$sTime){// 如果设置了验证时间，且验证时间不为all，而且验证时间不合模式相匹配，那么路过验证
					continue;
				}
				$bResult=true;
				switch(strtoupper($sCondition)){// 判断验证条件
					case Model::MUST_TO_CHECKDATE:// 必须验证不管表单是否有设置该字段
						$bResult=$this->checkField_($arrData,$arrCheckInfo);
						break;
					case Model::VALUE_TO_CHECKDATE:// 值不为空的时候才验证
						if(isset($arrData[$sProp]) and ''!=trim($arrData[$sProp]) and 0!=trim($arrData[$sProp])){
							$bResult=$this->checkField_($arrData,$arrCheckInfo);
						}
						break;
					default:// 默认表单存在该字段就验证
						if(isset($arrData[$sProp])){
							$bResult=$this->checkField_($arrData,$arrCheckInfo);
						}
						break;
				}
				if($bResult===Check::SKIP_OTHERS){
					break;
				}elseif(!$bResult){
					$arrError[$sProp][$sIndex]=$this->getErrorMessage();
					$this->setIsError(false);// 还原，防止下次认证错误
					$this->_sLastErrorMessage='';
					if(isset($arrPolicy['policy']) && !$arrPolicy['policy']['check_all_rules']){
						break;
					}
				}
			}
		}
		return $arrError;
	}
	private function checkField_($arrData,$arrCheckInfo){
		$bResult=true;
		switch($arrCheckInfo['extend']){
			case 'function':// 使用函数进行验证
			case 'callback':// 调用方法进行验证
				$arrArgs=isset($arrCheckInfo['rule'])?$arrCheckInfo['rule']:array();
				if(isset($arrData['field'])){
					array_unshift($arrArgs,$arrData['field']);
				}
				if('function'==$arrCheckInfo['extend']){
					if(function_exists($arrCheckInfo['extend'])){
						$bResult=call_user_func_array($arrCheckInfo['check'],$arrArgs);
					}else{
						Dyhb::E('Function is not exist');
					}
				}else{
					if(is_array($arrCheckInfo['check'])){// 如果$sContent为数组，那么该数组为回调，先检查一下
						if(!is_callable($arrCheckInfo['check'],false)){// 检查是否为有效的回调
							G::E('Callback is not exist');
						}
					}else{// 否则使用模型中的方法进行填充
						$oModel=null;
						eval('$oModel=new '.$this->_sClassName.'();');
						$bResult = call_user_func_array(array($oModel,$arrCheckInfo['check']),$arrArgs);
					}
				}
				if($bResult===false){
					if(empty($arrCheckInfo['message'])){
						$arrCheckInfo['message']=Dyhb::L('模型回调验证失败','__DYHB__@Dyhb');
					}
					$this->setErrorMessage($arrCheckInfo['message']);
				}
				return $bResult;
				break;
			case 'confirm': // 验证两个字段是否相同
				$bResult=$arrData[$arrCheckInfo['field']]==$arrData[$arrCheckInfo['check']];
				if($bResult===false){
					if(empty($arrCheckInfo['message'])){
						$arrCheckInfo['message']=Dyhb::L('模型验证两个字段是否相同失败','__DYHB__@Dyhb');
					}
					$this->setErrorMessage($arrCheckInfo['message']);
				}
				return $bResult;
				break;
			case 'in': // 验证是否在某个数组范围之内
				$bResult=in_array($arrData[$arrCheckInfo['field']],$arrData[$arrCheckInfo['check']]);
				if($bResult===false){
					if(empty($arrCheckInfo['message'])){
						$arrCheckInfo['message']=Dyhb::L('模型验证是否在某个范围失败','__DYHB__@Dyhb');
					}
					$this->setErrorMessage($arrCheckInfo['message']);
				}
				return $bResult;
				break;
			case 'equal': // 验证是否等于某个值
				$bResult= $arrData[$arrCheckInfo['field']]==$arrCheckInfo['check'];
				if($bResult===false){
					if(empty($arrCheckInfo['message'])){
						$arrCheckInfo['message']=Dyhb::L('模型验证是否等于某个值失败','__DYHB__@Dyhb');
					}
					$this->setErrorMessage($arrCheckInfo['message']);
				}
				return $bResult;
				break;
			case 'regex':
			default: // 默认使用正则验证 可以使用验证类中定义的验证名称
				$oCheck=Check::RUN();
				$bResult=Check::checkByArgs($arrCheckInfo['check'],$arrCheckInfo['rule']);
				if($bResult===Check::SKIP_OTHERS){
					break;
				}
				if(!$bResult){
					if(empty($arrCheckInfo['message'])){
						$arrCheckInfo['message']=$oCheck->getErrorMessage();
					}
					$this->setErrorMessage($arrCheckInfo['message']);
					return $bResult;
				}
				break;
		 }
		 return $bResult;
	}
	public function addCheck($sPropName,$Check){
		$arrP=array($sPropName=>array($Check));
		$arrR=$this->prepareCheckRules_($arrP);
		if (!empty($arrR[$sPropName]['rules'])){
			foreach($arrR[$sPropName]['rules'] as $rule){
				$this->_arrCheck[$sPropName]['rules'][]=$rule;
			}
		}
		return $this;
	}
	public function propCheck($sPropName){
		if(isset($this->_arrCheck[$sPropName])){
			return $this->_arrCheck[$sPropName];
		}
		return array('policy'=>self::$_arrCheckOptions,'rules'=>array());
	}
	public function allCheck(){
		return $this->_arrCheck;
	}
	public function removePropCheck($sPropName){
		if (isset($this->_arrCheck[$sPropName])){
			unset($this->_arrCheck[$sPropName]);
		}
		return $this;
	}
	public function removeAllCheck(){
		$this->_arrCheck=array();
		return $this;
	}
	public function __call($sMethodName,array $arrArgs){
		if (isset($this->_arrStaticMethods[$sMethodName])){
			$Callback=$this->_arrStaticMethods[$sMethodName];
			foreach($arrArgs as $arg){
				array_push($Callback[1],$arg);
			}
			return call_user_func_array($Callback[0],$Callback[1]);
		}
		Dyhb::E(Dyhb::L('未定义的方法 %s.','__DYHB__@Dyhb',null,$sMethodName));
	}
	public function setIsError($bIsError=false){
		$bOldValue=$this->_bIsError;
		$this->_bIsError=$bIsError;
		return $bOldValue;
	}
	public function setErrorMessage($sErrorMessage=''){
		$this->setIsError(true);
		$sOldValue=$this->_sLastErrorMessage;
		$this->_sLastErrorMessage=$sErrorMessage;
		return $sOldValue;
	}
	public function isError(){
		return $this->_bIsError;
	}
	public function getErrorMessage(){
		return $this->_sLastErrorMessage;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   数据库访问统一入口，可以通过这个接口与数据库进行交互($)*/
class Db{
	static public $_sDefaultFactoryName='DbFactoryMysql';
	public $_sFactoryName='';
	static public $_oFactory;
	public $_oConnect=null;
	static public $_arrWriteDbConfig=array();
	static  public $_arrReadDbConfig=array();
	public $_hWriteConnect=null;
	public $_arrHReadConnect=array();
	static public $_bSingleHost=true;
	static public $_bIsInitConnect=true;
	static private $_oDefaultDbIns;
	const PARAM_QM='?';// 问号作为参数占位符
	const PARAM_CL_NAMED=':';// 冒号开始的命名参数
	const PARAM_DL_SEQUENCE='$';// $符号开始的序列
	const PARAM_AT_NAMED='@';// @开始的命名参数
	const FETCH_MODE_ARRAY=1;// 返回的每一个记录就是一个索引数组
	const FETCH_MODE_ASSOC=2;// 返回的每一个记录就是一个以字段名作为键名的数组
	const HAS_ONE='has_one';// 一对一关联
	const HAS_MANY='has_many';// 一对多关联
	const BELONGS_TO='belongs_to';// 从属关联
	const MANY_TO_MANY='many_to_many';// 多对多关联
	const FIELD='field';// 字段
	const PROP='prop';// 属性
	static public $_arrDsn=array();
	public function __construct($Dsn=null){
		self::$_arrDsn=$arrDsn=$this->parseConfig($Dsn);
		if(!$GLOBALS['_commonConfig_']['DB_DISTRIBUTED']){
			self::$_arrWriteDbConfig=self::$_arrDsn;
			self::$_arrReadDbConfig=array();
			self::$_bSingleHost=true;
		}else{
			$arrReadWrite=$this->parseReadWrite();
			self::$_arrWriteDbConfig=$arrReadWrite['master'];
			self::$_arrReadDbConfig=$arrReadWrite['slave'];
			self::$_bSingleHost=$arrReadWrite['single_host'];
		}
		$sFactoryName=isset($arrDsn['db_type'])?'DbFactory'.ucfirst(strtolower($arrDsn['db_type'])):self::$_sDefaultFactoryName;
		$this->_sFactoryName=$sFactoryName;
		// 创建工厂&开始连接
		self::$_oFactory=new $sFactoryName();
		$this->_oConnect=self::$_oFactory->createConnect();
	}
	static public function createDbInstance($Dsn=null,$sId=null ,$bDefaultIns=true,$bConnect=true){
		// 如果默认数据库对象存在，又选用默认的，则直接返回
		if($bDefaultIns and self::$_oDefaultDbIns){
			return self::$_oDefaultDbIns;
		}
		// 创建一个数据库Db对象
		$oDb=new self($Dsn);
		if($bConnect){
			$oDb->connect(self::$_arrWriteDbConfig,self::$_arrReadDbConfig,self::$_bSingleHost,self::$_bIsInitConnect);
		}
		// 设置全局对象
		if($bDefaultIns){
			self::$_oDefaultDbIns=$oDb;
		}
		return $oDb;
	}
	public function getFactory(){
		return self::$_oFactory;
	}
	protected function parseConfig($Config=''){
		$arrDsn=array();
		if(is_array($Config) && !G::oneImensionArray($Config)){
			$arrDsn=$Config;
		}elseif(!empty($Config['db_dsn'])){// 如果DSN字符串则进行解析
			$arrDsn[]=$this->parseDsn($Config['db_dsn']);
		}elseif(is_array($Config) && G::oneImensionArray($Config)){
			$arrDsn[]=$Config;
		}elseif(!empty($GLOBALS['_commonConfig_']['DB_GLOBAL_DSN'])){
			$arrDsn=$GLOBALS['_commonConfig_']['DB_GLOBAL_DSN'];
		}
		if(!$GLOBALS['_commonConfig_']['DB_DISTRIBUTED']){
			$arrDsn=$this->fillFull(isset($arrDsn[0])?$arrDsn[0]:array());
			return $arrDsn;
		}else{
			foreach($arrDsn as $nKey=>&$arrValue){
				$arrValue=$this->fillFull($arrValue);
			}
			return $arrDsn;
		}
	}
	protected function fillFull($arrConfig=array()){
		return array_merge($arrConfig,
			array('db_type'=>$GLOBALS['_commonConfig_']['DB_TYPE'],'db_schema'=>$GLOBALS['_commonConfig_']['DB_SCHEMA'],
				'db_user'=>$GLOBALS['_commonConfig_']['DB_USER'],
				'db_password'=>$GLOBALS['_commonConfig_']['DB_PASSWORD'],
				'db_host'=>$GLOBALS['_commonConfig_']['DB_HOST'],
				'db_port'=>$GLOBALS['_commonConfig_']['DB_PORT'],
				'db_name'=>$GLOBALS['_commonConfig_']['DB_NAME'],
				'db_prefix'=>$GLOBALS['_commonConfig_']['DB_PREFIX'],
				'db_dsn'=>$GLOBALS['_commonConfig_']['DB_DSN'],
				'db_params'=>$GLOBALS['_commonConfig_']['DB_PARAMS']
			)
		);
	}
	protected function parseDsn($sDsn){
		// dsn为空，直接返回
		if(empty($sDsn)){
			return false;
		}
		// 分析dsn参数
		$arrInfo=parse_url($sDsn);
		if($arrInfo['scheme']){
			$arrDsn=array(
				'db_type'=>$arrInfo['scheme'],
				'db_schema'=>$arrInfo['scheme'],
				'db_user'=>isset($arrInfo['user'])?$arrInfo['user']:'',
				'db_password'=>isset($arrInfo['pass'])?$arrInfo['pass']:'',
				'db_host'=>isset($arrInfo['host'])?$arrInfo['host']:'',
				'db_port'=>isset($arrInfo['port'])?$arrInfo['port']:'',
				'db_name'=>isset($arrInfo['path'])?substr($arrInfo['path'],1):'',
				'db_prefix'=>$GLOBALS['_commonConfig_']['DB_PREFIX']
			);
		}else{
			preg_match('/^(.*?)\:\/\/(.*?)\:(.*?)\@(.*?)\:([0-9]{1,6})\/(.*?)$/',trim($sDsn),$arrMatches);
			$arrDsn=array(
				'db_type'=>$arrMatches[1],
				'db_schema'=>$arrMatches[1],
				'db_user'=>$arrMatches[2],
				'db_password'=>$arrMatches[3],
				'db_host'=>$arrMatches[4],
				'db_port'=>$arrMatches[5],
				'db_name'=>$arrMatches[6],
				'db_prefix'=>$GLOBALS['_commonConfig_']['DB_PREFIX']
			);
		}
		return $arrDsn;
	}
	public function parseReadWrite(){
		$arrDsn=self::$_arrDsn;
		$bSingleHost=true;
		if($GLOBALS['_commonConfig_']['DB_RW_SEPARATE']){
			$arrMaster=array_shift($arrDsn);
		}else{
			$arrMaster=$arrDsn[floor(mt_rand(0,count($arrDsn)-1))];
		}
		$arrSlave=array();
		if(count($arrDsn)>0){
			$arrSlave=$arrDsn;
			$bSingleHost=false;
		}
		$arrResult=array('master'=>$arrMaster,'slave'=>$arrSlave,'single_host'=>$bSingleHost);
		self::$_arrDsn=null;
		return $arrResult;
	}
	public function connect($arrMasterConfig=array(),$arrSlaveConfig=array(),$bSingleHost=true,$bIsInitConnect=false,$sId=null){
		return $this->_oConnect->connect($arrMasterConfig,$arrSlaveConfig,$bSingleHost,$bIsInitConnect,$sId);
	}
	public function disConnect($hDbConnect=null,$bCloseAll=false){
		return $this->_oConnect->disConnect($hDbConnect ,$bCloseAll);
	}
	static public function RUN($Dsn=null,$sId=null ,$bDefaultIns=true,$bConnect=true){
		return self::createDbInstance($Dsn ,$sId,$bDefaultIns ,$bConnect);
	}
	public function addConnect($Config,$nLinkNum=null){
		$arrDsn=$this->parseConfig($Config);
		$arrReadWrite=$this->parseReadWrite();
		$arrReadDbConfig=$arrReadWrite['slave'];
		return $this->_oConnect->addConnect($arrReadDbConfig,$nLinkNum);
	}
	public function switchConnect($nLinkNum){
		return $this->_oConnect->switchConnect($nLinkNum);
	}
	public function setConnect(DbConnect $oConnect){ }
	public function getConnect(){
		return $this->_oConnect;
	}
	public function selectDb($sDbName,$hDbHandle=null){
		return $this->_oConnect->selectDb($sDbName,$hDbHandle);
	}
	public function query($Sql,$sDb=''){
		return $this->_oConnect->query($Sql ,$sDb);
	}
	public function insert(array $arrData,$sTableName='',array $RstrictedFields=null,$bReplace=false){
		$sType=$bReplace?'REPLACE':'INSERT';
		$arrHolders=$this->_oConnect->getPlaceHolder($arrData,$RstrictedFields);
		$sSql=$sType.' INTO '.$this->_oConnect->qualifyId($sTableName).'(';
		if($this->_oConnect->getBindEnabled()){
			$arrFields=array();// 使用参数绑定
			$arrValues=array();
			foreach($arrHolders as $sKey=>$arrH){
				list($sHolder,$sFieldName)=$arrH;
				$arrFields[]=$sFieldName;
				$arrValues[$sKey]=$sHolder;
			}
			$sSql.=implode(',',$arrFields).')VALUES('.implode(',',$arrValues).')';
			$oStmt=$this->_oConnect->prepare($sSql);
			foreach($arrValues as $sKey=>$arrHolder){
				if($arrData[$sKey] instanceof DbExpression){
					$oStmt->bindParam($arrHolder,$arrData[$sKey]->makeSql($this->_oConnect,$sTableName));
				}else{
					$oStmt->bindParam($arrHolder,$arrData[$sKey]);
				}
			}
			return $oStmt->exec();
		}else{
			$arrFields=array();
			$arrValues=array();
			foreach($arrHolders as $sKey=>$arrH){
				list(,$sFieldName)=$arrH;
				if($arrData[$sKey] instanceof DbExpression){
					$sValue=$this->_oConnect->qualifyStr($arrData[$sKey]->makeSql($this->_oConnect,$sTableName));
					if(strtolower($sValue)!=='null'){
						$arrFields[]=$sFieldName;
						$arrValues[]=$sValue;
					}
				}else{
					$sValue=$this->_oConnect->qualifyStr($arrData[$sKey]);
					if(strtolower($sValue)!=='null'){
						$arrFields[]=$sFieldName;
						$arrValues[]=$sValue;
					}
				}
				unset($arrData[$sKey]);
			}
			$sSql.=implode(',',$arrFields).')VALUES('.implode(',',$arrValues).')';
			return $this->_oConnect->exec($sSql);
		}
	}
	public function delete($sTableName,$arrWhere=null,$Order=null,$Limit=null){
		list($arrWhere)=$this->_oConnect->parseSqlInternal($sTableName,$arrWhere);
		$sSql='DELETE FROM '.$this->_oConnect->qualifyId($sTableName);
		if($arrWhere){
			$sSql.=' WHERE '.$arrWhere;
		}
		if($Order){
			$sSql.='ORDER BY '.$Order;
		}
		if($Limit){
			$sSql.='LIMIT '.$Limit;
		}
		$this->_oConnect->exec($sSql);
	}
	public function update($sTableName,$Row,array $Where=null,$Limit='',$Order='',array $RstrictedFields=null){
		list($Where)=$this->_oConnect->parseSqlInternal($sTableName,$Where);
		if($Where){
			$Where=' WHERE '.$Where;
		}
		if(!is_array($Row) && !($Row instanceof DbExpression)){
			Dyhb::E(Dyhb::L('$arrRow的格式只能是数组和DbException的实例。','__DYHB__@DbDyhb'));
		}
		if(!is_array($Row)){
			$Row=$Row->makeSql($this->_oConnect,$sTableName);
		}
		if($Order){
			$Order='ORDER BY '.$Order;
		}
		if($Limit){
			$Limit='LIMIT '.$Limit;
		}
		$sSql='UPDATE '.$this->_oConnect->qualifyId($sTableName).' SET ';
		$arrHolders=$this->_oConnect->getPlaceHolder($Row,$RstrictedFields);
		if($this->_oConnect->getBindEnabled()){
			$arrPairs=array();// 使用参数绑定
			$arrValues=array();
			foreach($arrHolders as $sKey=>$arrH){
				list($sHolder,$sFieldName)=$arrH;
				$arrPairs[]=$sFieldName.'='.$sHolder;
				$arrValues[$sKey]=$sHolder;
			}
			$sSql.=implode(',',$arrPairs);
			$sSql.="{$Where}{$Order}{$Limit};";
			$oStmt=$this->_oConnect->prepare($sSql);
			foreach($arrValues as $sKey=>$sHolder){
				if($Row[$sKey] instanceof DbExpression){
					$oStmt->bindParam($sHolder,$Row[$sKey]->makeSql($this->_oConnect,$this->_sTableName));
				}else{
					$oStmt->bindParam($sHolder,$Row[$sKey]);
				}
			}
			$oStmt->exec();
		}else{
			$arrPairs=array();
			foreach($arrHolders as $sKey=>$arrH){
				list($sHolder,$sFieldName)=$arrH;
				$sPair=$sFieldName.'=';
				if($Row[$sKey] instanceof DbExpression){
					$sPair.=$this->_oConnect->qualifyStr($Row[$sKey]->makeSql($this->_oConnect,$this->_sTableName));
				}else{
					$sPair.=$this->_oConnect->qualifyStr($Row[$sKey]);
				}
				$arrPairs[]=$sPair;
			}
			$sSql.=implode(',',$arrPairs);
			$sSql.="{$Where}{$Order}{$Limit};";
			$this->_oConnect->exec($sSql);
		}
	}
	public function select($TableName){
		$oSelect=new DbSelect($this->_oConnect);
		$oSelect->from($TableName);
		$arrArgs=func_get_args();
		if(!empty($arrArgs)){
			call_user_func_array(array($oSelect,'where'),$arrArgs);
		}
		return $oSelect;
	}
	public function getFullTableName($sTableName=''){
		return $this->getConnect()->getFullTableName($sTableName);
	}
	public function getOne($sSql,$arrInput=null){
		$oResult=$this->_oConnect->selectLimit($sSql,0,1,$arrInput);
		return $oResult->getRow(0);
	}
	public function getAllRows($sSql,array $arrInput=null){
		$oResult=$this->_oConnect->exec($sSql,$arrInput);
		return $oResult->getAllRows();
	}
	public function getRow($sSql,array $arrInput=null){
		$oResult=$this->_oConnect->selectLimit($sSql,0,1,$arrInput);
		return $oResult->getRow();
	}
	public function getCol($sSql,$nCol=0,array $arrInput=null){
		$oResult=$this->_oConnect->exec($sSql,$arrInput);
		return $oResult->fetchCol($nCol);
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   数据库表入口($)*/
class DbTableEnter{
	public $_sSchema;
	public $_sName;
	public $_sPrefix;
	protected $_pk;
	protected $_bIsCpk;
	protected $_nPkCount;
	protected static $_arrMeta=array();
	protected static $_arrFields=array();
	protected $_bInited;
	protected $_arrCurrentDbConfig;
	protected $_bIsError=false;
	protected $_sErrorMessage;
	protected $_oConnect;
	protected $_oDb;
	protected static $_arrDsn=array();
	public function __construct(array $arrConfig=null){
		$this->_arrConfig=$arrConfig;
		$arrConfig=array_shift($arrConfig);
		if(!empty($arrConfig['db_schema'])){
			$this->_sSchema=$arrConfig['db_schema'];
		}
		if(!empty($arrConfig['table_name'])){
			$this->_sName=$arrConfig['table_name'];
		}
		if(!empty($arrConfig['db_prefix'])){
			$this->_sPrefix=$arrConfig['db_prefix'];
		}
		if(!empty($arrConfig['pk'])){
			$this->_pk=$arrConfig['pk'];
		}
		if(!empty($arrConfig['connect'])){
			$this->setConnect($arrConfig['connect']);
		}
	}
	public function insert(array $arrRow,$bReturnPkValues=false){
		if(!$this->_bInited){
			$this->init();
		}
		$arrInsertId=array();
		if($bReturnPkValues){
			$bUseAutoIncr=false;
			if($this->_bIsCpk){
				foreach($this->_pk as $sPk){// 假定复合主键必须提供所有主键的值
					$arrInsertId[$sPk]=$arrRow[$sPk];
				}
			}else{
				$sPk=isset($this->_pk[0])?$this->_pk[0]:'';// 如果只有一个主键字段，并且主键字段不是自增，则通过 nextId() 获得一个主键值
				if(empty($arrRow[$sPk])){
					unset($arrRow[$sPk]);
					if(isset(self::$_arrMeta[$this->_sCacheId][$sPk]) and !self::$_arrMeta[$this->_sCacheId][$sPk]['auto_incr']){
						$arrRow[$sPk]=$this->nextId($sPk);
						$arrInsertId[$sPk]=$arrRow[$sPk];
					}else{
						$bUseAutoIncr=true;
					}
				}else{
					$arrInsertId[$sPk]=$arrRow[$sPk];
				}
			}
		}else{
			$sPk=$this->_pk[0];
			if(!$this->_bIsCpk && ! self::$_arrMeta[$this->_sCacheId][$sPk]['auto_incr'] && empty($arrRow[$sPk])){
				$sPk=$this->_pk[0];// 如果只有一个主键字段，并且主键字段不是自增，则通过 nextID() 获得一个主键值
				$arrRow[$sPk]=$this->nextId($sPk);
			}
		}
		$this->getDb()->insert($arrRow,$this->getFullTableName(),self::$_arrFields[$this->_sCacheId]);
		if($bReturnPkValues){
			if($bUseAutoIncr){// 创建主表的记录成功后，尝试获取新记录的主键值
				$arrInsertId[$sPk]=$this->_oConnect->getInsertId();
			}
			return $arrInsertId;
		}else{
			return false;
		}
	}
	public function delete($Where /* 最后两个参数为order,limit,如果没有这个条件，请务必在后面添加上null,或者‘’占位 */){
		if(!$this->_bInited){
			$this->init();
		}
		if(is_int($Where) || ((int)$Where==$Where && $Where>0)){
			if($this->_bIsCpk){// 如果 $Where 是一个整数，则假定为主键字段值
				Dyhb::E(Dyhb::L('使用复合主键时，不允许通过直接指定主键值来删除记录。' ,'__DYHB__@DbDyhb'));
			}else{
				$Where=array(array($this->_pk[0]=>(int)$Where));
			}
		}else{
			$Where=func_get_args();
		}
		if(count($Where)>=3){
			$limit=array_pop($Where);// Limit
			$order=array_pop($Where);// Order
		}else{
			$limit='';// Limit
			$order='';// Order
		}
		if($limit===null){
			$limit='';
		}
		if($order===null){
			$order ='';
		}
		$this->getDb()->delete($this->getFullTableName(),$Where,$order,$limit);
		return $this->_oConnect->getAffectedRows();
	}
	public function update($Row,$Where=null/* 最后两个参数为order,limit,如果没有这个条件，请务必在后面添加上null,或者‘’占位 */){
		if(!$this->_bInited){
			$this->init();
		}
		if(is_null($Where)){
			if(is_array($Row)){
				$Where=array();
				foreach($this->_pk as $sPk){
					if(!isset($Row[$sPk]) || strlen($Row[$sPk]==0)){
						$Where=array();
						break;
					}
					$Where[$sPk]=$Row[$sPk];
				}
				$Where=array($Where);
			}else{
				$Where=null;
			}
		}elseif($Where){
			$Where=func_get_args();
			array_shift($Where);
		}
		if(count($Where)>=3){
			$limit=array_pop($Where);// Limit
			$order=array_pop($Where);// Order
		}else{
			$limit='';// Limit
			$order='';// Order
		}
		if($limit===null){
			$limit='';
		}
		if($order===null){
			$order ='';
		}
		$this->getDb()->update($this->getFullTableName(),$Row,$Where,$order,$limit,self::$_arrFields[$this->_sCacheId]);
		return $this->_oConnect->getAffectedRows();
	}
	public function tableSelect(){
		if(!$this->_bInited){
			$this->init();
		}
		$oSelect=$this->_oDb->select($this);
		return $oSelect;
	}
	public function nextId($sFieldName=null,$nStart=1){
		if(!$this->_bInited){
			$this->init();
		}
		if(is_null($sFieldName)){
			$sFieldName=$this->_pk[0];
		}
		return $this->_oConnect->nextId($this->getFullTableName(),$sFieldName,$nStart);
	}
	public function getDb(){
		if(!$this->_bInited){
			$this->init();
		}
		return $this->_oDb;
	}
	public function setDb($oDb){
		if(!$this->_bInited){
			$this->init();
		}
		$this->_oDb=$oDb;
	}
	public function getConnect(){
		if(!$this->_bInited){
			$this->init();
		}
		return $this->_oConnect;
	}
	public function setConnect(DbConnect $oConnect){
		static $oDbObjParseDsn=null;
		$this->_oConnect=$oConnect;
		if(empty($this->_sSchema)){
			$this->_sSchema=$oConnect->getSchema();
		}
		if(empty($this->_sPrefix)){
			$this->_sPrefix=$oConnect->getTablePrefix();
		}
	}
	public function getFullTableName(){
		if(!$this->_bInited){
			$this->setupConnect_();
		}
		return (!empty($this->_sSchema)?"`{$this->_sSchema}`.":'')."`{$this->_sPrefix}{$this->_sName}`";
	}
	public function columns(){
		if(!$this->_bInited){
			$this->init();
		}
		return self::$_arrMeta[$this->_sCacheId];
	}
	public function getPk(){
		if(!$this->_bInited){
			$this->init();
		}
		return $this->_pk;
	}
	public function setPk($Pk){
		$oldValue=$this->_pk;
		$this->_pk=Dyhb::normalize($Pk);
		$this->_nPkCount=count($this->_pk);
		$this->_bIsCpk=$this->_nPkCount>1;
		return $oldValue;
	}
	public function isCompositePk(){
		if(!$this->_bInited){
			$this->init();
		}
		return $this->_bIsCpk;
	}
	public function init(){
		if($this->_bInited){
			return;
		}
		$this->_bInited=true;
		$this->setupConnect_();
		$this->setupTableName_();
		$this->setupMeta_();
		$this->setupPk_();
	}
	protected function setupConnect_(){
		if(!is_null($this->_oConnect)){
			return;
		}
		$oDb=Db::RUN($this->_arrConfig);
		$this->setConnect($oDb->getConnect());
		$this->setDb($oDb);
	}
	protected function setupTableName_(){
		if(empty($this->_sName)){
			$this->_sName=substr($this->_sName,0,-2);
		}elseif(strpos($this->_sName,'.')){
			list($this->_sChema,$this->_sName)=explode('.',$this->_sName);
		}
	}
	protected function setupMeta_(){
		$sTableName=$this->getFullTableName();
		$this->_sCacheId=trim($sTableName,'`');
		if(isset(self::$_arrMeta[$this->_sCacheId])){
			return;
		}
		$bCached=$GLOBALS['_commonConfig_']['DB_META_CACHED'];
		if($bCached){
			$arrData=Dyhb::cache($this->_sCacheId.'_'.md5($this->_sCacheId),'',
				array('encoding_filename'=>false,
					'cache_path'=>(defined('DB_META_CACHED_PATH')?DB_META_CACHED_PATH:APP_RUNTIME_PATH.'/Data/DbMeta')
				)
			);
			if(is_array($arrData) && !empty($arrData)){
				self::$_arrMeta[$this->_sCacheId]=$arrData[0];
				self::$_arrFields[$this->_sCacheId]=$arrData[1];
				return;
			}
		}
		$arrMeta=$this->_oConnect->metaColumns($sTableName);
		$arrFields=array();
		foreach($arrMeta as $field){
			$arrFields[$field['name']]=true;
		}
		self::$_arrMeta[$this->_sCacheId]=$arrMeta;
		self::$_arrFields[$this->_sCacheId]=$arrFields;
		$arrData=array($arrMeta,$arrFields);
		if($bCached){
			Dyhb::cache($this->_sCacheId.'_'.md5($this->_sCacheId),$arrData,
				array('encoding_filename'=>false,
					'cache_path'=>(defined('DB_META_CACHED_PATH')?DB_META_CACHED_PATH:APP_RUNTIME_PATH.'/Data/DbMeta')
				)
			);
		}
	}
	protected function setupPk_(){
		if (empty($this->_pk)){
			$this->_pk=array();
			foreach(self::$_arrMeta[$this->_sCacheId] as $arrField){
				if($arrField['pk']){
					$this->_pk[]=$arrField['name'];
				}
			}
		}
		$this->setPk($this->_pk);
	}
	protected function setIsError($bIsError=false){
		$bOldValue=$this->_bIsError;
		$this->_bIsError=$bIsError;
		return $bOldValue;
	}
	protected function setErrorMessage($sErrorMessage=''){
		$this->setIsError(true);
		$sOldValue=$this->_sErrorMessage;
		$this->_sErrorMessage=$sErrorMessage;
		return $sOldValue;
	}
	public function isError(){
		return $this->_bIsError;
	}
	public function getErrorMessage(){
		return $this->_sErrorMessage;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   Mysql数据库工厂类，用于生成数据库相关对象($)*/
class DbFactoryMysql extends DbFactory{
	public function createConnect(){
		return new DbConnectMysql();
	}
	public function createRecordSet(DbConnect $oConn,$nFetchMode=Db::FETCH_MODE_ASSOC){
		return new DbRecordSetMysql($oConn,$nFetchMode);
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   数据库工厂类，用于生成数据库相关对象 < 抽象类 >($)*/
abstract class DbFactory{
	abstract public function createConnect();
	abstract public function createRecordSet(DbConnect $oConnect);
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   所有数据库连接类的基类($)*/
abstract class DbConnect{
	static public $_nQueryCount=0;
	protected $_bDebug=false;
	protected $_nFetchMode=Db::FETCH_MODE_ASSOC;
	protected $_sSchema='';
	public $_arrWriteDbConfig=array();
	public $_arrReadDbConfig=array();
	public $_arrCurrentDbConfig=array();
	public $_bSingleHost=true;
	public $_bIsInitConnect=false;
	public $_hWriteConnect=null;
	public $_arrHReadConnect=array();
	public $_arrHConnect=array();
	public $_hCurrentConnect=null;
	protected $_bPConnect =false;
	public $_nVersion;
	protected $_bConnected;
	protected $_bLogEnabled=FALSE;
	protected $_sLastSql='';
	protected $_hQueryResult=null;
	protected $_bIsRuntime=true;
	protected $_nRunTime=0;
	protected $_sDefaultDatabase='';
	protected $_nTransTimes=0;
	protected $_sPrimary;
	protected $_sAuto;
	protected $_bResultFieldNameLower=false;
	protected $_sParamStyle=Db::PARAM_QM;
	protected $_nTrueValue=1; //int
	protected $_nFalseValue=0; //int
	protected $_sNullValue='NULL'; //string
	protected $_bBindEnabled=false;
	protected $_arrComparison=array(
		'eq'=>'=',
		'neq'=>'!=',
		'gt'=>'>',
		'egt'=>'>=',
		'lt'=>'<',
		'elt'=>'<=',
		'notlike'=>'NOT LIKE',
		'like'=>'LIKE'
	);
	protected $_sTableName='';
	public function __construct(){
		if($GLOBALS['_commonConfig_']['APP_DEBUG']){
			$this->_bDebug=true;
		}
		$this->_bLogEnabled=$GLOBALS['_commonConfig_']['LOG_SQL_ENABLED'];
	}
	protected function debug(){
		if($this->_bDebug){// 记录操作结束时间
			Log::R(" RunTime:".$this->getQueryTime()."s SQL=".$this->getLastSql(),Log::SQL,true);
		}
	}
	public function Q($nTimes=''){
		if(empty($nTimes)){
			return self::$_nQueryCount++;
		}else{
			self::$_nQueryCount++;
		}
	}
	public function connect($arrMasterConfig=array(),$arrSlaveConfig=array(),$bSingleHost=true,$bIsInitConnect=false){
		if (is_array($arrMasterConfig) && !empty($arrMasterConfig)){// 配置主服务器数据
			$this->_arrWriteDbConfig=$arrMasterConfig;
		}
		$this->_bSingleHost=$bSingleHost;// 设置初始化
		$this->_bIsInitConnect=$bIsInitConnect;
		if($this->_bIsInitConnect){// 初始化连接
			if(!$this->writeConnect()){// 尝试连接主服务器 < 写 >
				return false;
			}
			if($GLOBALS['_commonConfig_']['DB_RW_SEPARATE'] || !$this->_bSingleHost){
				if(!is_array($arrSlaveConfig) || empty($arrSlaveConfig)){// 其他服务器数据
					$this->_arrReadDbConfig=$arrSlaveConfig;
				}else{
					$this->_arrReadDbConfig=$arrMasterConfig;
				}
				if($this->readConnect()){
					return false;
				}
			}
		}
	}
	abstract public function commonConnect($Config='',$nLinkid=0);
	abstract public function disConnect($hDbConnect=null,$bCloseAll=false);
	public function switchConnect($nLinkNum){
		if(isset($this->_arrHConnect[$nLinkNum])){// 存在指定的数据库连接序号
			$this->_hCurrentConnect=$this->linkids[$nLinkNum];
			return true;
		}else{
			return false;
		}
	}
	public function addConnect($Config,$nLinkNum=null){
		if(!is_array($Config) || empty($Config)){
			return false;
		}
		if(empty($nLinkNum)){
			$nLinkNum=count($this->_arrHConnect);
		}
		if(isset($this->_arrHConnect[$nLinkNum ])){
			return false;
		}
		// 创建新的数据库连接
		$this->_hCurrentConnect=$this->commonConnect($Config,$nLinkNum);
		$this->_arrHReadConnect[$nLinkNum ]=$this->_hCurrentConnect;
		$this->_arrHConnect[$nLinkNum ]=$this->_hCurrentConnect;
		$this->_sDefaultDatabase=$Config['db_name'];
		return $this->_hCurrentConnect;
	}
	public function writeConnect(){
		// 判断是否已经连接
		if($this->_hWriteConnect && is_resource($this->_hWriteConnect)){
			return $this->_hWriteConnect;
		}
		// 没有连接开始请求连接
		$hDb=$this->commonConnect($this->_arrWriteDbConfig);
		if(!$hDb || !is_resource($hDb)){
			return false;
		}
		$this->_hWriteConnect=$hDb;
		return $this->_hWriteConnect;
	}
	public function readConnect(){
		if(!$GLOBALS['_commonConfig_']['DB_RW_SEPARATE']){
			return $this->writeConnect();
		}
		// 如果有可用的Slave连接，随机挑选一台Slave
		if(is_array($this->_arrHReadConnect) && !empty($this->_arrHReadConnect)){
			$nKey=array_rand($this->_arrHReadConnect);
			if(isset($this->_arrHReadConnect[$nKey]) && is_resource($this->_arrHReadConnect[$nKey])){
				return $this->_arrHReadConnect[$nKey];
			}else{
				return false;
			}
		}
		// 连接到所有Slave数据库，如果没有可用的Slave机则调用Master
		if(!is_array($this->_arrReadDbConfig) || empty($this->_arrReadDbConfig)){
			return $this->writeConnect();
		}
		// 读服务器连接
		$this->_arrHReadConnect=array();
		$arrReadDbConfig=$this->_arrReadDbConfig;
		foreach($arrReadDbConfig as $arrRead){
			$hDb=$this->commonConnect($arrRead);
			if($hDb && is_resource($hDb)){
				$this->_arrHReadConnect[]=$hDb;
			}
		}
		// 如果没有一台可用的Slave则调用Master
		if(!is_array($this->_arrHReadConnect) || empty($this->_arrHReadConnect)){
			$this->errorMessage('Not availability slave db connection,call master db connection');
			return $this->writeConnect();
		}
		// 随机在已连接的Slave机中选择一台
		$sKey=array_rand($this->_arrHReadConnect);
		if(isset($this->_arrHReadConnect[$sKey]) && is_resource($this->rdbConn[$sKey])){
			return $this->_arrHReadConnect[$sKey];
		}
		// 如果选择的slave机器是无效的，并且可用的slave机器大于一台则循环遍历所有能用的slave机器
		if(count($this->_arrHReadConnect)>1){
			foreach($this->_arrHReadConnect as $hConnect){
				if(is_resource($hConnect)){
					return $hConnect;
				}
			}
		}
		// 如果没有可用的Slave连接，则继续使用Master连接
		return $this->writeConnect();
	}
	abstract public function query_($sSql,$bIsMaster=false);
	abstract public function selectDb($sDbName,$hDbHandle=null);
	abstract public function databaseVersion($nLinkid=0);
	abstract public function errorMessage($sMsg='',$hConnect=null);
	public function selectLimit($sSql,$nOffset=0,$nLength=30,$arrInput=null,$bLimit=true){
		if($bLimit===true){
			if(!is_null($nOffset)){
				$sSql.=' LIMIT '.(int)$nOffset;
				if(!is_null($nLength)){
					$sSql.=','.(int)$nLength;
				}else{
					$sSql.=',18446744073709551615';
				}
			}elseif(!is_null($nLength)){
				$sSql.=' LIMIT '.(int)$nLength;
			}
		}
		return $this->exec($sSql,$arrInput);
	}
	abstract public function getDatabaseNameList();
	abstract public function getTableNameList($sDbName=null);
	abstract public function getColumnNameList($sTableName,$sDbName=null);
	abstract public function isDatabaseExists($sDbName);
	abstract public function isTableExists($sTableName,$sDbName=null);
	public function getFullTableName($sTableName=''){
		$sSchema=isset($this->_arrCurrentDbConfig['db_schema'])?$this->_arrCurrentDbConfig['db_schema']:'';
		$sPrefix=isset($this->_arrCurrentDbConfig['db_prefix'])?$this->_arrCurrentDbConfig['db_prefix']:'';
		$sName=!empty($sTableName)?$sTableName:(isset($this->_arrCurrentDbConfig['table_name'])?$this->_arrCurrentDbConfig['table_name']:'');
		return(!empty($sSchema)?"`{$sSchema}`." :'')."`{$sPrefix}{$sName}`";
	}
	public function dumpNullString($Value){
		if(is_array($Value)){
			foreach($Value as $sKey=>$sValue){
				$Value[$sKey]=$this->dumpNullString($sValue);
			}
		}else{
			if(!isset($Value) || is_null($Value)){
				 $Value='NULL';
			}
		}
		return $Value;
	}
	public function query($Sql,$sDb=''){
		// 切换到指定数据库
		$sOldDb=$this->getCurrentDb();
		if($sDb and $sDb!=$sOldDb){
			$sOldDB=$this->selectDb($sDb);
		}
		// 执行
		$bRes=$this->query_($Sql);
		// 还原到以前的数据库
		if($sOldDb){
			$this->selectDb($sOldDb);
		}
		// 错误处理
		if($bRes===false){
			Dyhb::E(Dyhb::L('一条 SQL 语句在执行中出错:%s','__DYHB__@DbDyhb',null,$Sql));
		}
		return $bRes;
	}
	public function exec($sSql,$arrInput=null){
		// 如果有给定占位符，解析SQL
		if(is_array($arrInput)){
			$sSql=$this->fakeBind($sSql,$arrInput);
		}
		$hResult=$this->query_($sSql);
		if(is_resource($hResult)){
			$oDbRecordSet=Db::$_oFactory->createRecordSet($this,$this->_nFetchMode);
			$oDbRecordSet->setQueryResultHandle($hResult);
			return $oDbRecordSet;
		}elseif($hResult){
			return $hResult;
		}else{
			$sMoreMessage='';
			if($this->getErrorCode()==1062){
				$sMoreMessage=Dyhb::L('主键重复','__DYHB__@DbDyhb').' Error:<br/>'.Dyhb::L('你的操作中出现了重复记录，请修正错误！','__DYHB__@DbDyhb');
			}
			Dyhb::E($sMoreMessage);
		}
	}
	abstract public function getInsertId();
	abstract function getNumRows($hRes=null);
	abstract public function getAffectedRows();
	abstract public function lockTable($sTableName);
	abstract public function unlockTable($sTableName);
	abstract public function setAutoCommit($bAutoCommit=false);
	abstract public function startTransaction();
	abstract public function endTransaction();
	abstract public function commit();
	abstract public function rollback();
	public function getOne($sSql,$arrInput=null,$bLimit=true){
		$oResult=$this->selectLimit($sSql,0,1,$arrInput,$bLimit);
		if($oResult===false){
			return false;
		}
		return $oResult->getRow(0);
	}
	public function getAllRows($sSql,array $arrInput=null){
		$oResult=$this->exec($sSql,$arrInput);
		if($oResult===false){
			return false;
		}
		return $oResult->getAllRows();
	}
	public function getRow($sSql,array $arrInput=null,$bLimit=true){
		$oResult=$this->selectLimit($sSql,0,1,$arrInput,$bLimit);
		if($oResult===false){
			return false;
		}
		return $oResult->getRow();
	}
	public function getCol($sSql,$nCol=0,array $arrInput=null){
		$oResult=$this->exec($sSql,$arrInput);
		if($oResult===false){
			return false;
		}
		return $oResult->fetchCol($nCol);
	}
	public function getComparison(){
		return $this->_arrComparison;
	}
	public function getBindEnabled(){
		return $this->_bBindEnabled;
	}
	public function getTrueValue(){
		return $this->_nTrueValue;
	}
	public function getFalseValue(){
		return $this->_nFalseValue;
	}
	public function getNullValue(){
		return $this->_sNullValue;
	}
	public function getParamStyle(){
		return $this->_sParamStyle;
	}
	public function getResultFieldNameLower(){
		return $this->_bResultFieldNameLower;
	}
	public function setResultFieldNameLower($bIsLower=true){
		$bOldValue=$this->_bResultFieldNameLower;
		$this->_bResultFieldNameLower=$bIsLower;
		return $bOldValue;
	}
	public function setLogEnabled($bLogEnabled=true){
		$bOldValue=$this->_bLogEnabled;
		$this->_bLogEnabled=$bLogEnabled;
		return $bOldValue;
	}
	public function setConnectHandle($hConnectHandle){
		if(!is_resource($hConnectHandle)){
			Dyhb::E(Dyhb::L('参数 $hConnectHandle 必须是有效的数据库连接','__DYHB__@DbDyhb'));
		}
		$hOldValue=$this->_hCurrentConnect;
		$this->_hCurrentConnect=$hConnectHandle;
		$this->_hWriteConnect=$hConnectHandle;
		return $hOldValue;
	}
	public function isConnected(){
		return $this->_bConnected;
	}
	public function getCurrentDb(){
		return $this->_sDefaultDatabase;
	}
	public function getQueryResult(){
		return $this->_hQueryResult;
	}
	public function getCurrentConnect(){
		return $this->_hCurrentConnect;
	}
	public function getErrorCode(){
		return $this->_nErrorCode;
	}
	public function getSchema(){
		return !empty($this->_arrCurrentDbConfig['db_schema'])?$this->_arrCurrentDbConfig['db_schema']:$GLOBALS['_commonConfig_']['DB_SCHEMA'];
	}
	public function getTablePrefix(){
		return !empty($this->_arrCurrentDbConfig['db_prefix'])?$this->_arrCurrentDbConfig['db_prefix']:$GLOBALS['_commonConfig_']['DB_PREFIX'];
	}
	protected function setLastSql($Sql){
		$sOldValue=$this->_sLastSql;
		$this->_sLastSql=$Sql;
		return $sOldValue;
	}
	public function getLastSql(){
		return $this->_sLastSql;
	}
	protected function setQueryTime($nSpecSec){
		$nOldValue=$this->_nRunTime;
		$this->_nRunTime=$nSpecSec;
		return $nOldValue;
	}
	public function getQueryTime(){
		return $this->_nRunTime;
	}
	public function getQueryFormatTime(){
		if($this->_nRunTime){
			return sprintf("%.6f sec",$this->_nRunTime);
		}
		return 'NULL';
	}
	public function getTransTimes(){
		return $this->_nTransTimes;
	}
	public function getPrimary(){
		return $this->_sPrimary;
	}
	public function getAuto(){
		return $this->_sAuto;
	}
	protected function setPConnect($bPConnect){
		$bOldValue=$this->_bPConnect;
		$this->_bPConnect=$bPConnect;
		return $bOldValue;
	}
	public function getPConnect(){
		return $this->_bPConnect;
	}
	public function getVersion(){
		return $this->_nVersion;
	}
	public function qualifyId($sName,$sAlias=null,$sAs=null){
		$sName=str_replace('`','',$sName);// 过滤'`'字符
		if(strpos($sName,'.')===false){// 不包含表名字
			$sName=$this->identifier($sName);
		}else{
			$arrArray=explode('.',$sName);
			foreach($arrArray as $nOffset=>$sName){
				if(empty($sName)){
					unset($arrArray[$nOffset]);
				}else{
					$arrArray[$nOffset]=$this->identifier($sName);
				}
			}
			$sName=implode('.',$arrArray);
		}
		if($sAlias){
			return "{$sName} {$sAs} ".$this->identifier($sAlias);
		}else{
			return $sName;
		}
	}
	abstract public function identifier($sName);
	public function qualifySql($sSql,$sTableName,array $arrMapping=null,$hCallback=null){
		if(empty($sSql)){
			return '';
		}
		$arrMatches=null;
		preg_match_all('/\[[a-z][a-z0-9_\.]*\]|\[\*\]/i',$sSql,$arrMatches,PREG_OFFSET_CAPTURE);
		$arrMatches=reset($arrMatches);
		if(!is_array($arrMapping)){
			$arrMapping=array();
		}
		$sOut='';
		$nOffset=0;
		foreach($arrMatches as $arrM){
			$nLen=strlen($arrM[0]);
			$sField=substr($arrM[0],1,$nLen-2);
			$arrArray=explode('.',$sField);
			switch(count($arrArray)){
				case 3:
					$sF=(!empty($arrMapping[$arrArray[2]]))?$arrMapping[$arrArray[2]]:$arrArray[2];
					$sTable="{$arrArray[0]}.{$arrArray[1]}";
					break;
				case 2:
					$sF=(!empty($arrMapping[$arrArray[1]]))?$arrMapping[$arrArray[1]]:$arrArray[1];
					$sTable=$arrArray[0];
					break;
				default:
					$sF=(!empty($arrMapping[$arrArray[0]]))?$arrMapping[$arrArray[0]]:$arrArray[0];
					$sTable=$sTableName;
			}
			if($hCallback){
				$sTable=call_user_func($hCallback,$sTable);
			}
			$sField=$this->qualifyId("{$sTable}.{$sF}");
			$sOut.=substr($sSql,$nOffset,$arrM[1]-$nOffset).$sField;
			$nOffset=$arrM[1]+$nLen;
		}
		$sOut.=substr($sSql,$nOffset);
		return $sOut;
	}
	public function qualifyIds($Names,$sAs=null){
		$arrArray=array();
		$Names=Dyhb::normalize($Names);
		foreach($Names as $sAlias=>$name){
			if(!is_string($sAlias)){
				$sAlias=null;
			}
			$arrArray[]=$this->qualifyId($name,$sAlias,$sAs);
		}
		return $arrArray;
	}
	public function qualifyInto($sSql,array $arrParams=null,$ParamStyle=null,$bReturnParametersCount=false){
		if(is_null($ParamStyle)){
			$ParamStyle=$this->getParamStyle();
		}
		$hCallback=array($this,'qualifyStr');
		switch($ParamStyle){
			case Db::PARAM_QM:
			case Db::PARAM_DL_SEQUENCE:
				if($ParamStyle ==Db::PARAM_QM){
					$arrParts=explode('?',$sSql);
				}else{
					$arrParts=preg_split('/\$[0-9]+/',$sSql);
				}
				$sStr=$arrParts[0];
				$nOffset=1;
				foreach($arrParams as $argValue){
					if(!isset($arrParts[$nOffset])){
						break;
					}
					if(is_array($argValue)){
						$argValue=array_unique($argValue);
						$argValue=array_map($hCallback,$argValue);
						$sStr.=implode(',',$argValue).$arrParts[$nOffset];
					}else{
						$sStr.=$this->qualifyStr($argValue).$arrParts[$nOffset];
					}
					$nOffset++;
				}
				if($bReturnParametersCount){
					return array($sStr,count($arrParts));
				}else{
					return $sStr;
				}
			case Db::PARAM_CL_NAMED:
			case Db::PARAM_AT_NAMED:
				$sSplit=($ParamStyle==Db::PARAM_CL_NAMED)?':':'@';
				$arrParts=preg_split('/('.$sSplit.'[a-z0-9_\-]+)/i',$sSql,-1,PREG_SPLIT_DELIM_CAPTURE);
				$arrParts=array_filter($arrParts,'strlen');// 过滤空元素
				$nMax=count($arrParts);
				
				$sStr='';
				if($nMax<2){
					$sStr=$sSql;
				}else{
					for($nOffset=1;$nOffset<$nMax;$nOffset+=2){
						$sArgName=substr($arrParts[$nOffset],1);
						if(!isset($arrParams[$sArgName])){
							Dyhb::E(sprintf('Invalid parameter "%s" for "%s"',$sArgName,$sSql));
						}
						if(is_array($arrParams[$sArgName])){
							$argValue=array_map($hCallback,$arrParams[$sArgName]);
							$sStr.=$arrParts[$nOffset-1].$this->qualifyStr(implode(',',$argValue)).' ';
						}else{
							$sStr.=$arrParts[$nOffset-1].$this->qualifyStr($arrParams[$sArgName]);
						}
					}
				}
				if($bReturnParametersCount){
					return array($sStr,intval($nMax/2)-1);
				}else{
					return $sStr;
				}
			default:
				return $sSql;
		}
	}
	abstract public function qualifyStr($Value);
	public function qualifyWhere($Where,$sTableName=null,$arrFieldsMapping=null,$hCallback=null){
		$sWhereStr='';
		// 直接使用字符串条件
		if(is_string($Where)){
			$sWhereStr=$Where;
		}else{// 使用数组条件表达式
			if(array_key_exists('logic_',$Where)){
				// 定义逻辑运算规则 例如 OR XOR AND NOT
				$sOperate=' '.strtoupper($Where['logic_']).' ';
				unset($Where['logic_']);
			}else{
				$sOperate=' AND ';// 默认进行 AND 运算
			}
			foreach($Where as $sKey=>$val){
				$sWhereStr.='(';
				if(strlen($sKey)-1===stripos($sKey,'_')){
					$sWhereStr.=$this->qualifyDyhbWhere($sKey,$val,$sTableName,$arrFieldsMapping,$hCallback);// 解析特殊条件表达式
				}else{
					if(is_array($val)){
						if(isset($val[0]) && is_string($val[0])){
							if(preg_match('/^(EQ|NEQ|GT|EGT|LT|ELT|NOTLIKE|LIKE)$/i',$val[0])){ // 比较运算
								$arrComparison=$this->getComparison();
								$sWhereStr.=$this->qualifyWhereField($sKey,$sTableName,$arrFieldsMapping,$hCallback).' '.$arrComparison[strtolower($val[0])].
									' '.(isset($val[1])?$this->qualifyStr($val[1]):'');
							}elseif(isset($val[0]) && 'exp'==strtolower($val[0])){ // 使用表达式
								$sWhereStr.='('.$this->qualifyWhereField($sKey,$sTableName,$arrFieldsMapping,$hCallback).' '.(isset($val[1])?$val[1]:'').') ';
							}elseif(isset($val[0]) && preg_match('/IN/i',$val[0])){ // IN 运算
								if(isset($val[1]) && is_string($val[1])){
									$val[1]=explode(',',$val[1]);
								}
								$sZone=implode(',',(isset($val[1])?$this->qualifyStr($val[1]):''));
								$sWhereStr.=$this->qualifyWhereField($sKey,$sTableName,$arrFieldsMapping,$hCallback).' '.strtoupper($val[0]).'('.$sZone.')';
							}elseif(preg_match('/BETWEEN/i',$val[0])){ // BETWEEN运算
								$arrData=isset($val[1]) && is_string($val[1])?explode(',',$val[1]):(isset($val[1])?$this->qualifyStr($val[1]):'');
								$sWhereStr.='('.$this->qualifyWhereField($sKey,$sTableName,$arrFieldsMapping,$hCallback).' BETWEEN '.
									(isset($arrData[0])?$arrData[0]:'').' AND '.(isset($arrData[1])?$arrData[1] :'').')';
							}else{
								Dyhb::E(Dyhb::L('表达式错误%s','__DYHB__@DbDyhb',null,(isset($arrData[0])?$arrData[0]:'')));
							}
						}else{
							$nCount=count($val);
							$sTemp=strtoupper(trim((isset($val[$nCount-1]))?$val[$nCount-1]:''));
							if(in_array($sTemp,array('AND','OR','XOR'))){
								$sRule=$sTemp;
								$nCount=$nCount-1;
							}else{
								$sRule='AND';
							}
							for($nI=0;$nI<$nCount;$nI++){
								$sData=isset($val[$nI]) && is_array($val[$nI]) && isset($val[$nI][1])?$val[$nI][1]:$val[ $nI ];
								if(isset($val[$nI]) && isset($val[$nI][0]) && 'exp'==strtolower($val[$nI][0])){
									$sWhereStr.='('.$this->qualifyWhereField($sKey,$sTableName,$arrFieldsMapping,$hCallback).' '.$sData.') '.$sRule.' ';
								}else{
									$arrComparison =$this->getComparison();
									$sOp=isset($val[$nI]) && is_array($val[$nI]) && isset($val[$nI][0])?$arrComparison[ strtolower($val[$nI][0]) ]:'=';
									$sWhereStr.='('.$this->qualifyWhereField($sKey,$sTableName,$arrFieldsMapping,$hCallback).' '.$sOp.' '.$this->qualifyStr($sData).') '.$sRule.' ';
								}
							}
							$sWhereStr=substr($sWhereStr,0,-4);
						}
					}else{
						$sWhereStr.=$this->qualifyWhereField($sKey,$sTableName,$arrFieldsMapping,$hCallback).'='.$this->qualifyStr($val);
					}
				}
				$sWhereStr.=')'.$sOperate;
			}
			if($sWhereStr){
				$sWhereStr=substr($sWhereStr,0,-strlen($sOperate));
			}else{
				$sWhereStr=trim($sOperate);
			}
		}
		return empty($sWhereStr) || $sWhereStr=='AND'?'':$sWhereStr;
	}
	public function qualifyDyhbWhere($sKey,$val,$sTableName=null,$arrFieldsMapping=null,$hCallback=null){
		$sWhereStr='';
		switch($sKey){
			case 'string_':// 字符串模式查询条件
				$sWhereStr=$val;
				break;
			case 'complex_':// 复合查询条件
				$sWhereStr=$this->qualifyWhere($val);
				break;
			case 'query_':
				$arrWhere=array();
				parse_str($val,$arrWhere);// 字符串模式查询条件
				if(array_key_exists('logic_',$arrWhere)){
					$sOp=' '.strtoupper($arrWhere['logic_']).' ';
					unset($arrWhere['logic_']);
				}else{
					$sOp=' AND ';
				}
				$arrValue=array();
				foreach($arrWhere as $sField=>$data){
					$arrValue[]=$this->qualifyWhereField($sField,$sTableName,$arrFieldsMapping,$hCallback).'='.$this->qualifyStr($data);
				}
				$sWhereStr=implode($sOp,$arrValue);
				break;
		}
		return $sWhereStr;
	}
	public function filterField($sKey,$sTableName){
		if(strpos($sKey,'.')){
			// 如果字段名带有 .，则需要分离出数据表名称和 schema
			$arrKey=explode('.',$sKey);
			switch(count($arrKey)){
				case 3:
					$sField=$this->qualifyId("{$arrKey[0]}.{$arrKey[1]}.{$arrKey[2]}");
					break;
				case 2:
					$sField=$this->qualifyId("{$arrKey[0]}.{$arrKey[1]}");
					break;
			}
		}else{
			$sField=$this->qualifyId("{$sTableName}.{$sKey}");
			$sField=$sKey;
		}
		return $sField;
	}
	public function qualifyWhereField($sField,$sTableName=null,$arrFieldsMapping=null,$hCallback=null){
		$sField=$this->filterField($sField,$sTableName);
		// 如果键名是一个字符串，则假定为 “字段名”=> “查询值” 这样的名值对
		$sField='['.trim($sField,'[]').']';
		return $this->qualifySql($sField,$sTableName,$arrFieldsMapping,$hCallback);
	}
	public function qualifyTable($sTableName,$sSchema=null,$sAlias=null){
		if(strpos($sTableName,'.')!==false){
			$arrParts=explode('.',$sTableName);
			$sTableName=$arrParts[1];
			$sSchema=$arrParts[0];
		}
		$sTableName=trim($sTableName,'"');
		$sSchema=trim($sSchema,'"');
		// public 是默认的schema
		if(strtoupper($sSchema)=='PUBLIC'){
			$sSchema='';
		}
		$sI=$sSchema !=''?"\"{$sSchema}\".\"{$sTableName}\"":"\"{$sTableName}\"";
		return empty($sAlias)?$sI:$sI." \"{$sAlias}\"";
	}
	public function qualifyField($sFieldName,$sTableName=null,$sSchema=null,$sAlias=null){
		$sFieldName=trim($sFieldName,'"');
		if(strpos($sFieldName,'.')!==false){
			$arrParts=explode('.',$sFieldName);
			if(isset($arrParts[2])){
				$sSchema=$arrParts[0];
				$sTableName=$arrParts[1];
				$sFieldName=$arrParts[2];
			}elseif(isset($arrParts[1])){
				$sTableName=$arrParts[0];
				$sFieldName=$arrParts[1];
			}
		}
		$sFieldName=($sFieldName == '*')?'*':"\"{$sFieldName}\"";
		if(!empty($sTableName)){
			$sFieldName=$this->qualifyTable($sTableName,$sSchema).'.'.$sFieldName;
		}
		return empty($sAlias)?$sFieldName:"{$sFieldName} AS \"{$sAlias}\"";
	}
	public function getPlaceHolder(array $arrInput,array $arrRestrictedFields=null,$sParamStyle=null){
		$arrHolders=array();
		foreach(array_keys($arrInput) as $nOffset=>$sKey){
			if($arrRestrictedFields && !isset($arrRestrictedFields[$sKey])){
				continue;
			}
			switch($sParamStyle){
				case Db::PARAM_QM:
					$arrHolders[$sKey]=array('?',$this->identifier($sKey));
					break;
				case Db::PARAM_DL_SEQUENCE:
					$arrHolders[$sKey]=array('$'.($nOffset+1),$this->identifier($sKey));
					break;
				default:
					$arrHolders[$sKey]=array("{$sParamStyle}{$sKey}",$this->identifier($sKey));
			}
		}
		return $arrHolders;
	}
	abstract public function nextId($sTableName,$sFieldName,$nStartValue=1);
	public function parseSql($sTableName){
		$arrArgs=func_get_args();
		array_shift($arrArgs);
		list($arrWhere)=$this->parseSqlInternal($sTableName,$arrArgs);
		return $arrWhere;
	}
	public function parseSqlInternal($sTableName,array $arrArgs=null){
		if(empty($arrArgs)){
			return array(null,null,null);
		}
		$sSql=array_shift($arrArgs);
		if(is_array($sSql)){
			return $this->parseSqlArray_($sTableName,$sSql,$arrArgs);
		}else{
			return $this->parseSqlString_($sTableName,$sSql,$arrArgs);
		}
	}
	public function parseSqlArray_($sTableName,array $arrValue,array $arrArgs){
		static $arrKeywords=array('('=>true,'AND'=>true,'OR'=>true,'NOT'=>true,
			'BETWEEN'=>true,'CASE'=>true,'&&'=>true,'||'=>true,'='=>true,
			'<=>'=>true,'>='=>true,'>'=>true,'<='=>true,'<'=>true,'<>'=>true,
			'!='=>true,'IS'=>true,'LIKE'=>true
		);
		$arrParts=array();
		$sNextOp='';
		$nArgsCount=0;
		$arrUsedTables=array();
		foreach($arrValue as $sKey=>$value){
			if(is_int($sKey)){
				// 如果键名是整数，则判断键值是否是关键字或 ')' 符号。
				// 如果键值不是关键字，则假定为需要再分析的 SQL，需要再次调用 parseSqlInternal() 进行分析。
				if(is_string($value) && isset($arrKeywords[strtoupper($value)])){
					$sNextOp='';
					$sSql=$value;
				}elseif($value==')'){
					$sNextOp='AND';
					$sSql=$value;
				}else{
					if($sNextOp!=''){
						$arrParts[]=$sNextOp;
					}
					array_unshift($arrArgs,$value);
					list($sSql,$arrUt,$nArgsCount)=$this->parseSqlInternal($sTableName,$arrArgs);
					array_shift($arrArgs);
					if(empty($sSql)){
						continue;
					}
					$arrUsedTables=array_merge($arrUsedTables,$arrUt);
					if($nArgsCount>0){
						$arrArgs=array_slice($arrArgs,$nArgsCount);
					}
					$sNextOp='AND';
				}
				$arrParts[]=$sSql;
			}else{
				if($sNextOp!=''){// 如果键名是字符串，则假定为字段名
					$arrParts[]=$sNextOp;
				}
				if(strpos($sKey,'.')){
					$arrKey=explode('.',$sKey);// 如果字段名带有 .，则需要分离出数据表名称和 schema
					switch(count($arrKey)){
					case 3:
						$arrUsedTables[]="{$arrKey[0]}.{$arrKey[1]}";
						break;
					case 2:
						$arrUsedTables[]=$arrKey[0];
						break;
					}
				}else{
					$sField=$sKey;
				}
				if(is_array($value)){
					if(G::oneImensionArray($value)){// where 条件分析器
						$value=array_unique($value);
					}
					$arrValues=array();
					foreach($value as $v){
						if($v instanceof DbExpression){
							$arrValues[]=$v->makeSql($this,$sTableName);
						}else{
							$arrValues[]=$v;
						}
					}
					$arrParts[]=$this->qualifyWhere(array($sField=>$arrValues),$this->_sTableName,null,null);
					unset($arrValues);
					unset($value);
				}else{
					if($value instanceof DbExpression){
						$value=$this->makeSql($this,$sTableName);
					}else{
						$value=$this->qualifyStr($value);
					}
					$arrParts[]=$sField.'='.$value;
				}
				$sNextOp='AND';
			}
		}
		$arrParts =array_unique($arrParts);// 过滤空值和重复值
		$arrParts=Dyhb::normalize($arrParts);
		return array(implode(' ',$arrParts),$arrUsedTables,$nArgsCount);
	}
	public function parseSqlString_($sTableName,$sWhere,array $arrArgs){
		$arrMatches=array();
		preg_match_all('/\[[a-z][a-z0-9_\.]*\]/i',$sWhere,$arrMatches,PREG_OFFSET_CAPTURE);
		$arrMatches=reset($arrMatches);
		$sOut='';
		$nOffset=0;
		$arrUsedTables=array();
		foreach($arrMatches as $arrM){
			$nLen=strlen($arrM[0]);
			$sField=substr($arrM[0],1,$nLen-2);
			$arrValue=explode('.',$sField);
			switch(count($arrValue)){
			case 3:
				$sSchema=$arrValue[0];
				$sTable=$arrValue[1];
				$sField=$arrValue[2];
				$arrUsedTables[]=$sSchema.'.'.$sTable;
				break;
			case 2:
				$sSchema=null;
				$sTable=$arrValue[0];
				$sField=$arrValue[1];
				$arrUsedTables[]=$sTable;
				break;
			default:
				$sSchema=null;
				$sTable=$sTableName;
				$sField=$arrValue[0];
			}
			$sField=$this->identifier("{$sSchema}.{$sTable}.{$sField}");
			$sOut.=substr($sWhere,$nOffset,$arrM[1]-$nOffset).$sField;
			$nOffset=$arrM[1]+$nLen;
		}
		$sOut.=substr($sWhere,$nOffset);
		$sWhere=$sOut;
		$nArgsCount=null;// 分析查询条件中的参数占位符
		if(strpos($sWhere,'?')!==false){
			$sRet=$this->qualifyInto($sWhere,$arrArgs,Db::PARAM_QM,true);// 使用 ?作为占位符的情况
		}elseif(strpos($sWhere,':')!==false){
			$sRet=$this->qualifyInto($sWhere,reset($arrArgs),Db::PARAM_CL_NAMED,true);// 使用 : 开头的命名参数占位符
		}else{
			$sRet=$sWhere;
		}
		if(is_array($sRet)){
			list($sWhere,$nArgsCount)=$sRet;
		}else{
			$sWhere=$sRet;
		}
		return array($sWhere,$arrUsedTables,$nArgsCount);
	}
	abstract public function metaColumns($sTableName);
	public function fakeBind($sSql,$arrInput){
		// 分析‘?’ 占位符
		if($arrInput===null){
			$arrInput=array();
		}
		$arrValue=explode('?',$sSql);
		$sSql=array_shift($arrValue);
		foreach($arrInput as $sValue){
			if(isset($arrValue[0])){
				$oDbQualifyId=$this->qualifyStr($sValue);
				$sSql.=$oDbQualifyId->makeSql().array_shift($arrValue);
			}
		}
		return $sSql;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   Mysql数据连接管理类($)*/
define('CLIENT_MULTI_RESULTS',131072);
class DbConnectMysql extends DbConnect{
	public function commonConnect($Config='',$nLinkid=0){
		if(!isset($this->_arrHConnect[$nLinkid ])){
			$this->_arrCurrentDbConfig=$Config;// 赋值给当前数据库连接配置
			$nHost=$Config['db_host'].($Config['db_port']?":{$Config['db_port']}":'');// 端口处理
			if(empty($Config['connect'])){// 如果设置了数据连接，则不连接
				if($this->_bPConnect){// 是否永久连接
					$this->_arrHConnect[$nLinkid]=@mysql_pconnect($nHost,$Config['db_user'],$Config['db_password'],CLIENT_MULTI_RESULTS);
				}else{
					$this->_arrHConnect[$nLinkid]=@mysql_connect($nHost,$Config['db_user'],$Config['db_password'],true,CLIENT_MULTI_RESULTS);
				}
			}else{
				$this->_arrHConnect[$nLinkid]=$Config['connect'];
			}
			if(!$this->_arrHConnect[$nLinkid]){// 判断是否成功连接上数据
				Dyhb::E(Dyhb::L('数据库连接失败，请检查你的数据库信息是否正确，连接数据库的配置如下：%s','__DYHB__@DbDyhb',null,G::dump($Config,false)));
				return false;
			}
			$this->_hCurrentConnect=$this->_arrHConnect[$nLinkid];
			if(empty($Config['db_name'])|| !mysql_select_db($Config['db_name'],$this->_arrHConnect[$nLinkid])){// 尝试请求数据
				Dyhb::E(Dyhb::L('数据库不存在在或者错误，请检查你的数据库信息是否正确，连接数据库的配置如下：%s','__DYHB__@DbDyhb',null,G::dump($Config,false)));
				return false;
			}
			$nDbVersion=$this->databaseVersion();// 获取Mysql数据库版本,尝试兼容性纠正
			if($nDbVersion >="4.1"){// 使用UTF8存取数据库 需要mysql 4.1.0以上支持
				$sCharset=isset($Config['db_char'])?$Config['db_char']:$GLOBALS['_commonConfig_']['DB_CHAR'];// 获取数据库字符集
				if(!mysql_query("SET character_set_connection=".$sCharset.",character_set_results=".$sCharset.",character_set_client=binary")){
					Dyhb::E(sprintf("Set db_host ‘%s’ charset=%s failed.",$nHost,$sCharset));
					return false;
				}
			}
			if($nDbVersion>'5.0.1'){// 忽略严格模式
				if(!mysql_query("SET sql_mode=''",$this->_arrHConnect[$nLinkid])){
					Dyhb::E('Set sql_mode failed.',$this->_arrHConnect[$nLinkid]);
					return false;
				}
			}
			$this->_bConnected=true;// 标记连接成功
		}
		return $this->_arrHConnect[$nLinkid];
	}
	public function disConnect($hDbConnect=null,$bCloseAll=false){
		if($hDbConnect && is_resource($hDbConnect)){// 关闭指定数据库连接
			mysql_close($hDbConnect);
			$hDbConnect=null;
		}
		if($bCloseAll){// 关闭所有数据库连接
			if($this->_hWriteConnect && is_resource($this->_hWriteConnect)){
				mysql_close($this->_hWriteConnect);
				$this->_hWriteConnect=null;
			}
			if(is_array($this->_arrHReadConnect)&& !empty($this->_arrHReadConnect)){
				foreach($this->_arrHReadConnect as $hConnect){
					if($hConnect && is_resource($hConnect)){
						mysql_close($hConnect);
					}
				}
				$this->_arrHReadConnect=array();
			}
			$this->_arrHConnect=array();
		}
		return true;
	}
	public function query_($sSql,$bIsMaster=false){
		$sSql=trim($sSql);// 过滤SQL语句
		if($sSql==""){// sql语句为空，则返回错误
			$this->errorMessage('Sql query is empty.');
			return false;
		}
		if(!$GLOBALS['_commonConfig_']['DB_RW_SEPARATE'] || $this->_bSingleHost){// 是否只有一台数据库机器
			$bIsMaster=true;
		}
		$sType='';// 获取执行SQL的数据库连接
		if($bIsMaster){
			$sType=trim(strtolower(substr(ltrim($sSql),0,6)));
		}
		if($bIsMaster || $sType!="select"){// 主服务或者是非查询，那么连接写服务器
			$hDbConnect=$this->writeConnect();
		}else{// 否则连接读服务器
			$hDbConnect=$this->readConnect();
		}
		if(!$hDbConnect || !is_resource($hDbConnect)){
			$this->_hCurrentConnect=null;
			$this->errorMessage(sprintf("Not availability db connection. Query SQL:%s",$sSql));
			return;
		}
		$this->_hCurrentConnect=$hDbConnect;// 执行查询
		$this->setLastSql($sSql);// 记录最后查询的sql语句
		$this->_hQueryResult=null;
		if($this->_bIsRuntime){// 是否记录数据库查询时间
			$nStartTime=G::getMicrotime();
			$this->_hQueryResult=mysql_query($sSql,$hDbConnect);
			$nRunTime=G::getMicrotime()- $nStartTime; // 记录sql运行时间
			$this->setQueryTime($nRunTime);
		}else{// 直接查询
			$this->_hQueryResult=mysql_query($sSql,$hDbConnect);
		}
		$this->Q(1);
		if($this->_bLogEnabled){// 记录执行的SQL
			$this->debug();
		}
		if($this->_hQueryResult===false){// 判断数据库查询是否正确
			$this->errorMessage(sprintf("Query sql failed. SQL:%s",$sSql),$hDbConnect);
		}
		return $this->_hQueryResult;
	}
	public function selectDb($sDbName,$hDbHandle=null){
		if($hDbHandle && is_resource($hDbHandle)){// 重新选择一个连接的数据库
			if(!mysql_select_db($sDbName,$hDbHandle)){
				Dyhb::E('Select database:$sDbName failed.');
				return false;
			}
			return true;
		}
		if($this->_hWriteConnect && is_resource($this->_hWriteConnect)){// 重新选择所有连接的数据库&读数据库连接
			if(!mysql_select_db($sDbName,$this->_hWriteConnect)){
				Dyhb::E('Select database:$dbName failed.');
				return false;
			}
		}
		if(is_array($this->_arrHReadConnect && !empty($this->_arrHReadConnect))){// 写数据库连接
			foreach($this->_arrHReadConnect as $hConnect){
				if($hConnect && is_resource($hConnect)){
					if(!mysql_select_db($sDbName,$hConnect)){
						Dyhb::E('Select database:$sDbName failed.');
						return false;
					}
				}
			}
		}
		$this->_arrHConnect=array();// 重设所有数据库连接
		if(is_array($this->_arrHReadConnect) && !empty($this->_arrHReadConnect)){
			$this->_arrHConnect=array_merge($this->_arrHReadConnect);
		}
		$this->_arrHConnect[]=$this->_hWriteConnect;
		$this->_hCurrentConnect=$this->_hWriteConnect;// 将当前连接切换到主服务器
		return true;
	}
	public function databaseVersion($nLinkid=0){
		if(!$nLinkid){
			$nLinkid=$this->_hCurrentConnect;
		}
		if($nLinkid){
			$this->_nVersion=mysql_get_server_info($nLinkid);
		}else{
			$this->_nVersion=mysql_get_server_info();
		}
		return $this->_nVersion;
	}
	public function errorMessage($sMsg='',$hConnect=null){
		if($sMsg=='' && !$hConnect){// 不存在消息返回
			return false;
		}
		$sMsg="MySQL Error:<br/>{$sMsg}";// 错误消息
		if($hConnect && is_resource($hConnect)){
			$sMsg.="<br/>MySQL Message:<br/>".mysql_error($hConnect);
			$sMsg.="<br/>MySQL Code:<br/>".mysql_errno($hConnect);
			$this->_nErrorCode=mysql_errno($hConnect);
		}
		$sMsg.="<br/>MySQL Time:<br/>[". date("Y-m-d H:i:s")."]";
		Dyhb::E($sMsg);
	}
	public function selectLimit($sSql,$nOffset=0,$nLength=30,$arrInput=null,$bLimit=true){
		if($bLimit===true){
			if(!is_null($nOffset)){
				$sSql.=' LIMIT ' .(int)$nOffset;
				if(!is_null($nLength)){
					$sSql.=',' .(int)$nLength;
				}else{
					$sSql.=',18446744073709551615';
				}
			}elseif(!is_null($nLength)){
				$sSql.=' LIMIT ' .(int)$nLength;
			}
		}
		return $this->exec($sSql,$arrInput);
	}
	public function getDatabaseNameList(){
		$sSql="SHOW DATABASES ;";// 执行
		$hResult=$this->query_($sSql);
		if($hResult===false || !is_resource($hResult)){// 失败
			Dyhb::E(Dyhb::L('无法取得数据库名称清单','__DYHB__@DbDyhb'));
		}
		$arrReturn=array();// 获取结果
		while(($arrRes=mysql_fetch_row($hResult))!==false){
			$arrReturn[]=$arrRes[0];
		}
		return $arrReturn ;
	}
	public function getTableNameList($sDbName=null){
		// 确定数据库
		if($sDbName===null){
			$sQueryDb=$this->getCurrentDb();
		}else{
			$sQueryDb=&$sDbName;
		}
		$sSql="SHOW TABLES;";// 执行
		$hResult=$this->query($sSql,$sQueryDb);
		if($hResult===false || !is_resource($hResult)){// 失败
			Dyhb::E(Dyhb::L('无法取得数据表名称清单','__DYHB__@DbDyhb'));
			return false;
		}
		$arrReturn=array();
		while(($arrRes=mysql_fetch_row($hResult))!==false){
			$arrReturn[]=$arrRes[0];
		}
		return $arrReturn;
	}
	public function getColumnNameList($sTableName,$sDbName=null){
		if($sDbName===null){// 确定数据库
			$sQueryDb=$this->getCurrentDb();
		}else{
			$sQueryDb=&$sDbName;
		}
		$sSql="SHOW COLUMNS FROM {$sTableName}";// 执行
		$hResult=$this->query($sSql,$sQueryDb);
		if($hResult===false|| !is_resource($hResult)){// 失败
			Dyhb::E(Dyhb::L('无法取得数据表 < %s > 字段名称清单','__DYHB__@DbDyhb',null,$sTableName));
		}
		$arrReturn=array();
		while(($arrRes=mysql_fetch_object($hResult))!==false){
			if(is_object($arrRes)){// 进一步处理获取主键和自动增加
				$arrRes=get_object_vars($arrRes);
			}
			$arrReturn[]=$arrRes['Field'];// 获取结果
			$sPrimary=$arrRes['Key']=='PRI' ?$arrRes['Field']: $sPrimary;
			$sAuto=!empty($arrRes['Extra'])?$arrRes['Field']: $sAuto;
		}
		$this->_sPrimary=$sPrimary;// 获取主键和自动增长
		$this->_sAuto=$sAuto;
		$this->_arrColumnNameList=$arrReturn;
		return $arrReturn;
	}
	public function isDatabaseExists($sDbName){}
	public function isTableExists($sTableName,$sDbName=null){}
	public function getInsertId(){
		$hDbConnect=$this->writeConnect();
		if(($nLastId=mysql_insert_id($hDbConnect))>0){
			return $nLastId;
		}
		return $this->getOne("SELECT LAST_INSERT_ID()",'',true);
	}
	public function getNumRows($hRes=null){
		if(!$hRes || !is_resource($hRes)){
			$hRes=$this->_hQueryResult;
		}
		return mysql_num_rows($hRes);
	}
	public function getAffectedRows(){
		$hDbConnect=$this->writeConnect();
		if(($nAffetedRows=mysql_affected_rows($hDbConnect))>=0){
			return $nAffetedRows;
		}
		return $this->getOne("SELECT ROW_COUNT()","",true);
	}
	public function lockTable($sTableName){
		return $this->query_("LOCK TABLES $sTableName",true);
	}
	public function unlockTable($sTableName){
		return $this->query_("UNLOCK TABLES $sTableName",true);
	}
	public function setAutoCommit($bAutoCommit=false){
		$bAutoCommit=($bAutoCommit?1:0);
		return $this->query_("SET AUTOCOMMIT=$bAutoCommit",true);
	}
	public function startTransaction(){
		// 没有当前数据库连接，直接返回
		if(!$this->_hCurrentConnect){
			return false;
		}
		if($this->_nTransTimes==0 && !$this->query_("BEGIN")){// 数据rollback 支持
			mysql_query('START TRANSACTION',$this->_hCurrentConnect);
		}
		$this->_nTransTimes++;
		return;
	}
	public function endTransaction(){}
	public function commit(){
		if($this->_nTransTimes>0){
			$this->_nTransTimes=0;
			if(!$this->query_("COMMIT",true)){
				return false;
			}
		}
		return $this->setAutoCommit(true);
	}
	public function rollback(){
		if($this->_nTransTimes>0){
			$this->_nTransTimes=0;
			if(!$this->query_("ROLLBACK",true)){
				return false;
			}
		}
		return $this->setAutoCommit(true);
	}
	public function nextId($sTableName,$sFieldName,$nStartValue=1){
		$sSeqTableName=$this->qualifyId("{$sTableName}_{$sFieldName}_seq");
		$sNextSql=sprintf('UPDATE %s SET id=LAST_INSERT_ID(id + 1)',$sSeqTableName);
		$nStartValue=intval($nStartValue);
		$bSuccessed=false;
		try{
			$this->exec($sNextSql);// 首先产生下一个序列值
			if($this->getAffectedRows()>0){
				$bSuccessed=true;
			}
		}catch(Exception $e){
			$this->exec(sprintf('CREATE TABLE %s (id INT NOT NULL)',$sSeqTableName));// 产生序列值失败，创建序列表
		}
		if(!$bSuccessed){
			$count=$this->getOne(sprintf('SELECT COUNT(*)FROM %s',$sSeqTableName));
			$count=array_shift($count);
			if($count==0){// 没有更新任何记录或者新创建序列表，都需要插入初始的记录
				$sSql=sprintf('INSERT INTO %s VALUES (%s)',$sSeqTableName,$nStartValue);
				$this->exec($sSql);
			}
			$this->exec($sNextSql);
		}
		$nInsertId=$this->getInsertId();// 获得新的序列值
		return $nInsertId;
	}
	public function identifier($sName){
		return ($sName!='*')?"`{$sName}`":'*';
	}
	public function qualifyStr($Value){
		if(is_array($Value)){// 数组，递归
			foreach($Value as $nOffset=>$sV){
				$Value[$nOffset]=$this->qualifyStr($sV);
			}
			return $Value;
		}
		if(is_int($Value)){
			return $Value;
		}
		if(is_bool($Value)){
			return $Value?$this->getTrueValue():$this->getFalseValue();
		}
		if(is_null($Value)){// Null值
			return $this->getNullValue();
		}
		if($Value instanceof DbExpression){
			$Value=$Value->makeSql($this);
		}
		return "'".mysql_real_escape_string($Value,$this->getCurrentConnect())."'";
	}
	public function metaColumns($sTableName){
		static $arrTypeMapping=array(
			'bit'=>'int1',
			'tinyint'=>'int1',
			'bool'=>'bool',
			'boolean'=>'bool',
			'smallint'=>'int2',
			'mediumint'=>'int3',
			'int'=>'int4',
			'integer'=>'int4',
			'bigint'=>'int8',
			'float'=>'float',
			'double'=>'double',
			'doubleprecision'=> 'double',
			'float unsigned'=> 'float',
			'decimal'=>'dec',
			'dec'=>'dec',
			'date'=>'date',
			'datetime'=>'datetime',
			'timestamp'=>'timestamp',
			'time'=>'time',
			'year'=>'int2',
			'char'=>'char',
			'nchar'=>'char',
			'varchar'=>'varchar',
			'nvarchar'=>'varchar',
			'binary'=>'binary',
			'varbinary'=>'varbinary',
			'tinyblob'=>'blob',
			'tinytext'=>'text',
			'blob'=>'blob',
			'text'=>'text',
			'mediumblob'=>'blob',
			'mediumtext'=>'text',
			'longblob'=>'blob',
			'longtext'=>'text',
			'enum'=> 'enum',
			'set'=> 'set'
		);
		// 返回查询结果对象
		$oRs=$this->exec(sprintf('SHOW FULL COLUMNS FROM %s',$this->qualifyId($sTableName)));
		$arrRet=array();
		$oRs->_nFetchMode=Db::FETCH_MODE_ASSOC;
		$oRs->_bResultFieldNameLower=true;
		while(($arrRow=$oRs->fetch())!==false){
			$arrField=array();
			$arrField['name']=$arrRow['field'];
			$sType=strtolower($arrRow['type']);
			$arrField['scale']=null;
			$arrQuery=false;
			if(preg_match('/^(.+)\((\d+),(\d+)/',$sType,$arrQuery)){
				$arrField['type']=$arrQuery[1];
				$arrField['length']=is_numeric($arrQuery[2])?$arrQuery[2]:-1;
				$arrField['scale']=is_numeric($arrQuery[3])?$arrQuery[3]:-1;
			}elseif(preg_match('/^(.+)\((\d+)/',$sType,$arrQuery)){
				$arrField['type']=$arrQuery[1];
				$arrField['length']=is_numeric($arrQuery[2])?$arrQuery[2]:-1;
			}elseif(preg_match('/^(enum)\((.*)\)$/i',$sType,$arrQuery)){
				$arrField['type']=$arrQuery[1];
				$arrValue=explode(",",$arrQuery[2]);
				$arrField['enums']=$arrValue;
				$nLen=max(array_map("strlen",$arrValue))-2; // PHP >=4.0.6
				$arrField['length']=($nLen>0)?$nLen:1;
			}else{
				$arrField['type']=$sType;
				$arrField['length']=-1;
			}
			$arrField['ptype']=isset($arrTypeMapping[strtolower($arrField['type'])])?$arrTypeMapping[strtolower($arrField['type'])]:$arrField['type'];
			$arrField['not_null']=(strtolower($arrRow['null'])!='yes');
			$arrField['pk']=(strtolower($arrRow['key'])=='pri');
			$arrField['auto_incr']=(strpos($arrRow['extra'],'auto_incr')!==false);
			if($arrField['auto_incr']){
				$arrField['ptype']='autoincr';
			}
			$arrField['binary']=(strpos($sType,'blob')!==false);
			$arrField['unsigned']=(strpos($sType,'unsigned')!==false);
			$arrField['has_default']=$arrField['default']=null;
			if(!$arrField['binary']){
				$sD=$arrRow['default'];
				if(!is_null($sD)&& strtolower($sD)!='null'){
					$arrField['has_default']=true;
					$arrField['default']=$sD;
				}
			}
			if(!is_null($arrField['default'])){
				switch ($arrField['ptype']){
					case 'int1':
					case 'int2':
					case 'int3':
					case 'int4':
						$arrField['default']=intval($arrField['default']);
						break;
					case 'float':
					case 'double':
					case 'dec':
						$arrField['default']=doubleval($arrField['default']);
						break;
					case 'bool':
						$arrField['default']=(bool)$arrField['default'];
				}
			}
			$arrRet[strtolower($arrField['name'])]=$arrField;
		}
		return $arrRet;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   使用硬盘文件进行缓存($)*/
class FileCache{
	protected $_arrOptions=array(
		'serialize'=>true,
		'cache_time'=>86400,
		'cache_path'=>'',
		'cache_prefix'=>'~@',
	);
	static protected $_sStaticHead='<?php die(); ?>';
	static protected $_nStaticHeadLen=15;
	public function __construct(array $arrOptions=null){
		if(!is_null($arrOptions)){
			$this->_arrOptions=array_merge($this->_arrOptions,$arrOptions);
		}
		if(empty($this->_arrOptions['cache_path'])){
			$this->_arrOptions['cache_path']=APP_RUNTIME_PATH.'/Data';
		}
	}
	public function checkCache($sCacheName,$arrOptions,$nTime=-1){
		$sFilePath=$this->getCacheFilePath($sCacheName,$arrOptions);
		if(!is_file($sFilePath)){
			return true;
		}
 		return ($nTime!==-1 && filemtime($sFilePath)+$nTime<CURRENT_TIMESTAMP);
	}
	public function getCache($sCacheName,array $arrOptions=null){
		$arrOptions=$this->option($arrOptions);
		$sCacheFilePath=$this->getCacheFilePath($sCacheName,$arrOptions);
		clearstatcache();
		if(!is_file($sCacheFilePath)){ 
			return false; 
		}
		$hFp=fopen($sCacheFilePath,'rb');
		if(!$hFp){
			return false;
		}
		flock($hFp,LOCK_SH);
		$nLen=filesize($sCacheFilePath);
		$bMqr=get_magic_quotes_runtime();
		if(version_compare(PHP_VERSION,'5.3.0','<')){
			@set_magic_quotes_runtime(0);
		}
		
		// 头部的16个字节存储了安全代码
		$sHead=fread($hFp,self::$_nStaticHeadLen);
		$nLen-=self::$_nStaticHeadLen;
		
		do{
			// 检查缓存是否已经过期
			if($this->checkCache($sCacheName,$arrOptions,$arrOptions['cache_time'])){
				$EncryptTest=null;
				$Data=false;
				break;
			}
			if($nLen>0){
				$Data=fread($hFp,$nLen);
			}else{
				$Data=false;
			}
			if(version_compare(PHP_VERSION,'5.3.0','<')){
				@set_magic_quotes_runtime($bMqr);
			}
		}while(false);
		flock($hFp,LOCK_UN);
		fclose($hFp);
		if($Data===false){
			return false;
		}
		// 解码
		if($arrOptions['serialize']){
			$Data=unserialize($Data);
		}
		return $Data;
	}
	public function setCache($sCacheName,$Data,array $arrOptions=null){
		$arrOptions=$this->option($arrOptions);
		if($arrOptions['serialize']){
			$Data=serialize($Data);
		}
		$Data=self::$_sStaticHead.$Data;
		$sCacheFilePath=$this->getCacheFilePath($sCacheName,$arrOptions);
		$this->writeData($sCacheFilePath,$Data);
	}
	public function deleleCache($sCacheName,array $arrOptions=null){
		$arrOptions=$this->option($arrOptions);
		$sCacheFilePath=$this->getCacheFilePath($sCacheName,$arrOptions);
		if($this->existCache($sCacheName,$arrOptions)){
			unlink($sCacheFilePath);
		}
	}
	public function existCache($sCacheName,$arrOptions){
		$sCacheFilePath=$this->getCacheFilePath($sCacheName,$arrOptions);
		return is_file($sCacheFilePath);
	}
	protected function getCacheFilePath($sCacheName,$arrOptions){
		if(!is_dir($arrOptions['cache_path'])){
			G::makeDir($arrOptions['cache_path']);
		}
		return $arrOptions['cache_path'].'/'.$arrOptions['cache_prefix'].$sCacheName.'.php';
	}
	public function writeData($sFileName,$sData){
		!is_dir(dirname($sFileName)) && G::makeDir(dirname($sFileName));
		return file_put_contents($sFileName,$sData,LOCK_EX);
	}
	protected function option(array $arrOptions=null){
		return !is_null($arrOptions)?array_merge($this->_arrOptions,$arrOptions):$this->_arrOptions;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   模型行为扩展($)*/
abstract class ModelBehavior implements IModelCallback{
	protected $_oMeta;
	protected $_arrSettings=array();
	private $_arrDynamicMethods=array();
	private $_arrStaticMethods=array();
	private $_arrEventHandlers=array();
	private $_arrGetters=array();
	private $_arrSetters=array();
	protected $_bIsError=false;
	protected $_sLastErrorMessage;
	public function __construct(ModelMeta $oMeta,array $arrSettings){
		$this->_oMeta=$oMeta;// 设置元对象
		foreach($arrSettings as $sKey=>$sValue){
			if(array_key_exists($sKey,$this->_arrSettings)){
				$this->_arrSettings[$sKey]=$sValue;
			}
		}
		$this->bind();
	}
	static public function normalizeConfig($arrConfig){
		$arrRet=array();
		foreach($arrConfig as $key=>$value){
			if(is_int($key) && !is_array($value)){
				$arrRet[$value]=array();
			}else{
				$arrRet[$key]=$value;
			}
		}
		return $arrRet;
	}
	public function bind(){
		$this->addStaticMethod_('isBehaviorError',array($this,'isError')); // 判断模式扩展扩展是否出错
		$this->addStaticMethod_('getBehaviorErrorMessage',array($this,'getErrorMessage')); // 获取模式扩展的错误信息
		$this->addStaticMethod_('changeSettings',array($this,'changeSettings'));
	}
	public function unbind(){
		foreach($this->_arrDynamicMethods as $sMethodName){// 移除插件的动态方法
			$this->_oMeta->removeDynamicMethod($sMethodName);
		}
		foreach($this->_arrStaticMethods as $sMethodName){// 移除插件的动态方法
			$this->_oMeta->removeStaticMethod($sMethodName);
		}
		foreach($this->_arrEventHandlers as $arrValue){// 移除插件的事件处理函数
			list($sEventType,$callback)=$arrValue;
			$this->_oMeta->removeEventHandler($sEventType,$callback);
		}
		foreach($this->_arrGetters as $sPropName){// 移除插件的getter方法
			$this->_oMeta->unsetPropGetter($sPropName);
		}
		foreach($this->_arrSetters as $sPropName){// 移除插件的setter方法
			$this->_oMeta->unsetPropSetter($sPropName);
		}
	}
	protected function addDynamicMethod_($sMethodName,$Callback,$arrCustomParameters=array()){
		$this->_oMeta->addDynamicMethod($sMethodName,$Callback,$arrCustomParameters);
		$this->_arrDynamicMethods[]=$sMethodName;
	}
	protected function addStaticMethod_($sMethodName,$Callback,$arrCustomParameters=array()){
		$this->_oMeta->addStaticMethod($sMethodName,$Callback,$arrCustomParameters);
		$this->_arrStaticMethods[]=$sMethodName;
	}
	protected function addEventHandler_($nEventType,$Callback,$arrCustomParameters=array()){
		$this->_oMeta->addEventHandler($nEventType,$Callback,$arrCustomParameters);
		$this->_arrEventHandlers[]=array($nEventType,$Callback);
	}
	protected function setPropGetter_($sPropName,$Callback,$arrCustomParameters=array()){
		$this->_oMeta->setPropGetter($sPropName,$Callback,$arrCustomParameters);
		$this->_arrGetters[]=$sPropName;
	}
	protected function setPropSetter_($sPropName,$Callback,$arrCustomParameters=array()){
		$this->_oMeta->setPropSetter($sPropName,$Callback,$arrCustomParameters);
		$this->_arrSetters[]=$sPropName;
	}
	public function changeSettings($sName,$sValue){
		$this->_arrSettings[$sName]=$sValue;
	}
	
	public function setIsError($bIsError=false){
		$bOldValue=$this->_bIsError;
		$this->_bIsError=$bIsError;
		return $bOldValue;
	}
	public function setErrorMessage($sErrorMessage=''){
		self::setIsError(true);
		$sOldValue=$this->_sLastErrorMessage;
		$this->_sLastErrorMessage=$sErrorMessage;
		return $sOldValue;
	}
	public function isError(){
		return $this->_bIsError;
	}
	public function getErrorMessage(){
		return $this->_sLastErrorMessage;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   模型RBAC 行为扩展($)*/
class ModelBehaviorRbac extends ModelBehavior{
	protected $_arrSettings=array(
		'encode_type'=>'authcode',
		'authcode_random'=>6,
		'auth_thin'=>false,
		'userid_prop'=>'user_id',
		'username_prop'=>'user_name',
		'useremail_prop'=>'user_email',
		'status_prop'=>'user_status',
		'password_prop'=>'user_password',
		'authcode_random_prop'=>'user_random',
		'rbac_data_props'=>'user_name,user_id,user_email',
		'check_login_field'=>'',
		'update_login_auto'=>true,
		'update_login_count_prop'=>'user_logincount',
		'update_login_at_prop'=>'user_lastlogintime',
		'update_login_ip_prop'=>'user_lastloginip',
		'register_save_auto'=>true,
		'register_ip_prop'=>'user_registerip',
		'register_at_prop'=>'create_dateline',
		'unique_username'=>true,
		'rbac_login_life'=>86400
	);
	private $_arrSavedState=array();
	public function bind(){
		parent::bind();
		// 添加静态方法
		$this->addStaticMethod_('checkLogin',array($this,'checkLogin'));// 登录验证
		$this->addStaticMethod_('checkUsername',array($this,'checkUsername'));// 验证用户名是否存在
		$this->addStaticMethod_('checkPassword',array($this,'checkPassword'));// 验证密码
		$this->addStaticMethod_('changePassword',array($this,'changePassword'));// 修改密码
		$this->addStaticMethod_('authData',array($this,'getAuthData'));// 获取当前的加密登录信息
		$this->addStaticMethod_('userData',array($this,'userDataDyn'));// 获取当前用户登录信息
		$this->addStaticMethod_('checkRbac',array($this,'checkRbac'));// 启动权限检查
		$this->addStaticMethod_('isLogin',array($this,'isLogin'));// 判断是否登录
		$this->addStaticMethod_('alreadyLogout',array($this,'alreadyLogout'));// 检测是否已经登出
		$this->addStaticMethod_('checkRbacLogin',array($this,'checkRbacLogin'));// 检测是否已经登出
		$this->addStaticMethod_('logout',array($this,'logout'));// 登出
		$this->addStaticMethod_('replaceSession',array($this,'replaceSession'));
		$this->addStaticMethod_('updateSession',array($this,'updateSession'));
		$this->addStaticMethod_('getMenuList',array($this,'getMenuList'));
		$this->addStaticMethod_('getTopMenuList',array($this,'getTopMenuList'));
		$this->addStaticMethod_('clearThisCookie',array($this,'clearThisCookie'));
		$this->addStaticMethod_('initLoginlife',array($this,'initRbacloginlife'));// 登录时间设置
		// 添加动态方法
		$this->addDynamicMethod_('checkPassword',array($this,'checkPasswordDyn'));
		$this->addDynamicMethod_('changePassword',array($this,'changePasswordDyn'));
		$this->addDynamicMethod_('updateLogin',array($this,'updateLoginDyn'));
		$this->addDynamicMethod_('userData',array($this,'userDataDyn'));
		// 响应事件，用于更新认证相关数据
		$this->addEventHandler_(self::AFTER_CHECK_ON_CREATE,array($this,'afterCheckOnCreate_'));
		$this->addEventHandler_(self::AFTER_CHECK_ON_UPDATE,array($this,'afterCheckOnUpdate_'));
	}
	public function initRbacloginlife($nTime=86400){
		$this->_arrSettings['rbac_login_life']=$nTime;
	}
	public function checkLogin($sUsername,$sPassword,$bEmail=false,$bUpdateLogin=true){
		if(!empty($this->_arrSettings['check_login_field'])){
			$sPn=trim($this->_arrSettings['check_login_field']);
		}else{
			if($bEmail===true){// E-mail
				$sPn=$this->_arrSettings['useremail_prop'];
			}else{
				$sPn=$this->_arrSettings['username_prop'];
			}
		}
		$oMember=$this->_oMeta->find(array($sPn=>$sUsername))->query();
		if(!$oMember->id()){
			$this->setErrorMessage(Dyhb::L('我们无法找到%s这个用户','__DYHB__@RbacDyhb',null,$sUsername));
			return false;
		}
		if($oMember[$this->_arrSettings['status_prop']]<=0){// 查看用户是否禁用
			$this->setErrorMessage(Dyhb::L('用户%s的账户还没有解锁，你暂时无法登录' ,'__DYHB__@RbacDyhb',null,$sUsername));
			return false;
		}
		if(!$this->checkPasswordDyn($oMember,$sPassword)){// 验证密码
			$this->setErrorMessage(Dyhb::L('用户%s的密码错误','__DYHB__@RbacDyhb',null,$sUsername));
			return false;
		}
		if($this->_arrSettings['encode_type']=='authcode'){
			$this->tryToDeleteOldSession($oMember['user_id']);
		}
		if($this->_arrSettings['auth_thin']===TRUE){// 如果为简化版的验证，直接返回
			return $oMember;
		}
		$this->clearThisCookie();// 清除COOKIE
		Dyhb::cookie(md5($GLOBALS['_commonConfig_']['USER_AUTH_KEY']),$oMember->id(),$this->_arrSettings['rbac_login_life']);
		$arrAdmins=$GLOBALS['_commonConfig_']['ADMIN_USERID']?explode(',',$GLOBALS['_commonConfig_']['ADMIN_USERID']):array(1);
		if(in_array($oMember->id(),$arrAdmins)){
			Dyhb::cookie(md5($GLOBALS['_commonConfig_']['ADMIN_AUTH_KEY']),true,$this->_arrSettings['rbac_login_life']);
		}
		if($this->_arrSettings['update_login_auto']){
			$this->updateLoginDyn($oMember);
		}
		$this->sendCookie($oMember->id(),$oMember[$this->_arrSettings['password_prop']]);
		$sHash=Dyhb::cookie($GLOBALS['_commonConfig_']['RBAC_DATA_PREFIX'].'hash');
		$this->updateSession($sHash,$oMember->id(),$GLOBALS['_authkey_']);// 更新数据库中的登陆会话
		$this->saveAccessList($oMember->id());// 最后缓存rbac权限
		return $oMember;
	}
	public function tryToDeleteOldSession($nUserId){
		SessionModel::M()->deleteWhere("`user_id`=$nUserId OR(`user_id`=0)");
	}
	public function getAuthData($sUserModel=null){
		if($sUserModel===null || $sUserModel==''){
			$sUserModel=$GLOBALS['_commonConfig_']['USER_AUTH_MODEL'];
		}
		$sAuthKey=md5($GLOBALS['_commonConfig_']['DYHB_AUTH_KEY'].$_SERVER['HTTP_USER_AGENT']);
		$sAuthData=Dyhb::cookie($GLOBALS['_commonConfig_']['RBAC_DATA_PREFIX'].'auth');
		list($nUserId,$sPassword)=$sAuthData?explode("\t",G::authCode($sAuthData,true,NULL,$this->_arrSettings['rbac_login_life'])):array('','');
		$sHash=Dyhb::cookie($GLOBALS['_commonConfig_']['RBAC_DATA_PREFIX'].'hash');
		$nUserId=intval($nUserId);
		$arrUserInformation=array();// 用户信息容器
		$bSessionExists=false;
		if($sHash){
			if($nUserId){
				$oSessionDbExpression=new DbExpression('['.$GLOBALS['_commonConfig_']['DB_PREFIX'].$sUserModel.'.user_id]');
				$arrUserInformation=UserModel::F()
					->setColumns($this->_arrSettings['rbac_data_props'])
					->joinLeft(array($GLOBALS['_commonConfig_']['DB_PREFIX'].'session'),'user_id,session_hash,session_auth_key',array('user_id'=>$oSessionDbExpression))
					->where('['.$GLOBALS['_commonConfig_']['DB_PREFIX'].'session.session_hash]=? AND ['.
						$GLOBALS['_commonConfig_']['DB_PREFIX'].$sUserModel.'.user_id]=? AND ['.
						$GLOBALS['_commonConfig_']['DB_PREFIX'].$sUserModel.'.user_password]=? AND ['.
						$GLOBALS['_commonConfig_']['DB_PREFIX'].'session.session_auth_key]=? AND user_status > 0',
						array($sHash,$nUserId,$sPassword,$sAuthKey)
					)
					->asArray()
					->query();
				if($arrUserInformation['user_id'] && $arrUserInformation['session_hash']){
					$bSessionExists=TRUE;
				}
			}else{
				$arrUserInformation=array();
				$arrSessionData=SessionModel::F('session_hash=?',$sHash)->asArray()->query();
				if(!empty($arrSessionData['user_id'])){
					$bSessionExists=true;
					$this->updateSession($arrSessionData['session_hash'],$nUserId,$sAuthKey,true);
				}else{
					if(!G::isImplementedTo(($arrSessionData=SessionModel::F('session_hash=?',$sHash)->asArray()->query()),'IModel')){
						$this->clearThisCookie();
						$bSessionExists=TRUE;
					}
				}
			}
		}
		if($bSessionExists===FALSE){
			if($nUserId){
				if(!($arrUserInformation=UserModel::F('user_id=? AND user_password=? AND user_status > 0',$nUserId,$sPassword)->asArray()->query())){
					$this->clearThisCookie();
				}
			}
			$arrUserInformation['session_hash']=G::randString(6);
			$this->updateSession($arrUserInformation['session_hash'],$nUserId,$sAuthKey);
		}
		$sHash=isset($arrUserInformation['session_hash'])?$arrUserInformation['session_hash']:$sHash;// hash
		$nUserId=isset($arrUserInformation['user_id'])? $arrUserInformation['user_id']:$nUserId;// 用户名和用户密码
		$sUserName=isset($arrUserInformation['user_name'])? $arrUserInformation['user_name'] :'';
		if(!$sHash || $sHash!=Dyhb::cookie($GLOBALS['_commonConfig_']['RBAC_DATA_PREFIX'].'hash')){// 设置hash值
			Dyhb::cookie($GLOBALS['_commonConfig_']['RBAC_DATA_PREFIX'].'hash',$sHash,$this->_arrSettings['rbac_login_life']);
		}
		$GLOBALS[$sAuthKey]=$arrUserInformation;
		$GLOBALS['_authkey_']=$sAuthKey;
		return $arrUserInformation;
	}
	public function checkRbac(){
		$bAdminAuthKey=Dyhb::cookie(md5($GLOBALS['_commonConfig_']['ADMIN_AUTH_KEY']));
		if($bAdminAuthKey){
			return true;
		}
		if($GLOBALS['_commonConfig_']['USER_AUTH_ON'] && !in_array(MODULE_NAME,Dyhb::normalize($GLOBALS['_commonConfig_']['NOT_AUTH_MODULE']))){// 用户权限检查
			if(!$this->accessDecision()){
				if(!Dyhb::cookie(md5($GLOBALS['_commonConfig_']['USER_AUTH_KEY']))){// 检查认证识别号
					G::urlGoTo(Dyhb::U($GLOBALS['_commonConfig_']['USER_AUTH_GATEWAY']));// 跳转到认证网关
				}
				if($GLOBALS['_commonConfig_']['RBAC_ERROR_PAGE']){// 没有权限 抛出错误
					G::urlGoTo($GLOBALS['_commonConfig_']['RBAC_ERROR_PAGE']);
				}else{
					if($GLOBALS['_commonConfig_']['GUEST_AUTH_ON']){
						G::urlGoTo(Dyhb::U($GLOBALS['_commonConfig_']['USER_AUTH_GATEWAY']));
					}
					$this->setErrorMessage(Dyhb::L('你没有访问权限','__DYHB__@RbacDyhb'));
					return false;
				}
			}
		}
	}
	public function isLogin(){
		$arrUser=$GLOBALS[$GLOBALS['_authkey_']];
		return !empty($arrUser['user_id']);
	}
	public function alreadyLogout(){
		$arrUser=$GLOBALS[$GLOBALS['_authkey_']];
		return empty($arrUser['user_id']);
	}
	public function checkRbacLogin(){
		if($this->checkAccess()){// 检查当前操作是否需要认证
			if(!Dyhb::cookie(md5($GLOBALS['_commonConfig_']['USER_AUTH_KEY']))){// 检查认证识别号
				if($GLOBALS['_commonConfig_']['GUEST_AUTH_ON']){
					$arrAccessList=Dyhb::cookie('_access_list_');
					if($arrAccessList!==false){// 开启游客授权访问
						$this->saveAccessList($GLOBALS['_commonConfig_']['GUEST_AUTH_ID']);// 保存游客权限
					}
				}else{// 禁止游客访问跳转到认证网关
					G::urlGoTo(PHP_FILE.$GLOBALS['_commonConfig_']['USER_AUTH_GATEWAY']);
				}
			}
		}
		return true;
	}
	public function clearAllCookie(){
		Dyhb::cookie(null);
	}
	public function logout(){
		if($this->isLogin()){
			$this->clearThisCookie();
		}
	}
	public function clearThisCookie(){
		Dyhb::cookie(md5($GLOBALS['_commonConfig_']['USER_AUTH_KEY']),'',-1);
		Dyhb::cookie(md5($GLOBALS['_commonConfig_']['ADMIN_AUTH_KEY']),'',-1);
		Dyhb::cookie($GLOBALS['_commonConfig_']['RBAC_DATA_PREFIX'].'hash','',-1);
		Dyhb::cookie($GLOBALS['_commonConfig_']['RBAC_DATA_PREFIX'].'auth','',-1);
		Dyhb::cookie(md5(APP_NAME.MODULE_NAME.ACTION_NAME),'',-1);
	}
	public function checkUsername($sUsername){
		$sPn=$this->_arrSettings['username_prop'];
		if(!$this->_oMeta->find(array($sPn=>$sUsername))->getCounts()>0){
			$this->setErrorMessage(Dyhb::L('用户%s的不存在','__DYHB__@RbacDyhb',null,$sUsername));
			return false;
		}
		return true;
	}
	public function checkPassword($sUsername,$sPassword){
		$sPn=$this->_arrSettings['username_prop'];
		$oMember=$this->_oMeta->find(array($sPn=>$sUsername))->query();
		return($oMember->id() and $this->checkPasswordDyn($oMember,$sPassword));
	}
	public function changePassword($sUsername,$sNewPassword,$sOldPassword,$bIgnoreOldPassword=false){
		$sPn=is_int($sUsername)?$this->_arrSettings['userid_prop']:$this->_arrSettings['username_prop'];
		$oMember=$this->_oMeta->find(array($sPn=>$sUsername))->query();
		if(!$oMember->id()){
			$this->setErrorMessage(Dyhb::L('我们无法找到%s这个用户' ,'__DYHB__@RbacDyhb',null,$sUsername));
			return false;
		}
		$this->changePasswordDyn($oMember,$sNewPassword,$sOldPassword,$bIgnoreOldPassword);
	}
	public function checkPasswordDyn(Model $oMember,$sPassword){
		if($this->_arrSettings['auth_thin']===TRUE){
			return $this->checkPassword_($sPassword,$oMember[$this->_arrSettings['password_prop']]);
		}else{
			return $this->checkPassword_($sPassword,$oMember[$this->_arrSettings['password_prop']],$oMember[$this->_arrSettings['authcode_random_prop']]);
		}
	}
	public function changePasswordDyn(Model $oMember,$sNewPassword,$sOldPassword,$bIgnoreOldPassword=false){
		if(!$bIgnoreOldPassword){
			if(!$this->checkPasswordDyn($oMember, $sOldPassword)){
				$this->setErrorMessage(Dyhb::L('用户输入的旧密码错误','__DYHB__@RbacDyhb'));
				return false;
			}
		}
		$oMember->changePropForce($this->_arrSettings['password_prop'],$sNewPassword);
		$oMember->save(0,'update');
	}
	public function updateLoginDyn(Model $oMember,array $arrData=null){
		// 更新登录次数
		$sPn=$this->_arrSettings['update_login_count_prop'];
		if($sPn){
			$oMember->changePropForce($sPn,$oMember[$sPn]+1);
		}
		// 登录时间
		$sPn=$this->_arrSettings['update_login_at_prop'];
		if($sPn){
			$nTime=isset($arrData['login_at'])?$arrData['login_at']:CURRENT_TIMESTAMP;
			if(substr($this->_oMeta->_arrProp[$sPn]['ptype'],0,3)!='int'){
				$nTime=date('Y-m-d H:i:s',$nTime);
			}
			$oMember->changePropForce($sPn,$nTime);
		}
		// 登录当前登录IP
		$sPn=$this->_arrSettings['update_login_ip_prop'];
		if($sPn){
			$sIp=isset($arrData['login_ip'])?$arrData['login_ip']:isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:G::getIp();
			if(substr($this->_oMeta->_arrProp[$sPn]['ptype'],0,3)=='int'){
				$sIp=ip2long($sIp);
			}
			$oMember->changePropForce($sPn,$sIp);
		}
		$oMember->save(0,'update');
	}
	public function userDataDyn(){
		return $GLOBALS[$GLOBALS['_authkey_']];
	}
	public function afterCheckOnCreate_(Model $oMember){
		// 验证用户是否唯一
		if($this->_arrSettings['unique_username']){
			$sPn=$this->_arrSettings['username_prop'];
			if($this->_oMeta->find(array($sPn=> $oMember[$sPn]))->getCounts()>0){
				$this->setErrorMessage(Dyhb::L('用户名%s只能够唯一','__DYHB__@RbacDyhb',null,$oMember[$sPn]));
				return false;
			}
		}
		// 加密密码
		$sPn=$this->_arrSettings['password_prop'];
		$sPasswordCleartext=$oMember[$sPn];
		$oMember->changePropForce($sPn,$this->encodePassword_($sPasswordCleartext));
		// 发送验证random
		if(!$this->_arrSettings['auth_thin']){
			$oMember->changePropForce($this->_arrSettings['authcode_random_prop'],$this->_arrSavedState['authcode_random']);
		}
		$this->_arrSavedState['password']=$sPasswordCleartext;
		if($this->_arrSettings['register_save_auto']){// 如果设置了自动创建注册数据
			$sPn=$this->_arrSettings['register_at_prop'];// 创建注册时间数据
			if($sPn){
				$nTime=CURRENT_TIMESTAMP;
				if(substr($this->_oMeta->_arrProp[$sPn]['ptype'],0,3)!='int'){
					$nTime=date('Y-m-d H:i:s',$nTime);
				}
				$oMember->changePropForce($sPn,$nTime);
			}
			$sPn=$this->_arrSettings['register_ip_prop'];// 创建注册IP数据
			if($sPn){
				$sIp=isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:G::getIp();
				if(substr($this->_oMeta->_arrProp[$sPn]['ptype'],0,3)=='int'){
					$sIp=ip2long($sIp);
				}
				$oMember->changePropForce($sPn,$sIp);
			}
		}
	}
	public function afterCheckOnUpdate_(Model $oMember){
		$sPn=$this->_arrSettings['password_prop'];// 获取密码属性
		if($oMember->changed($sPn)){
			$sPasswordCleartext=$oMember[$sPn];
			$oMember[$sPn]=$this->encodePassword_($sPasswordCleartext);
			$this->_arrSavedState['password']=$sPasswordCleartext;
			if(!$this->_arrSettings['auth_thin']){
				$oMember->changePropForce($this->_arrSettings['authcode_random_prop'],$this->_arrSavedState['authcode_random']);
			}
		}
	}
	public function saveExceptionHandler_(Model $oMember){
		if(isset($this->_arrSavedState['password'])){// 还原密码
			$oMember->changePropForce($this->_arrSettings['password_prop'],$this->_arrSavedState['password']);
			unset($this->_arrSavedState['password']);
		}
	}
	private function checkPassword_($sCleartext,$sCryptograph,$sRanDom=''){
		$et=$this->_arrSettings['encode_type'];
		if(is_array($et)){
			return call_user_func($et,$sCleartext)==$sCryptograph;
		}
		if($et=='cleartext'){
			return $sCleartext==$sCryptograph;
		}
		switch($et){
			case 'authcode':
				return md5(md5($sCleartext).$sRanDom)==$sCryptograph;
			case 'md5':
				return md5($sCleartext)==$sCryptograph;
			case 'crypt':
				return crypt($sCleartext,$sCryptograph)==$sCryptograph;
			 case 'sha1':
				return sha1($sCleartext)==$sCryptograph;
			 case 'sha2':
				return hash('sha512', $sCleartext)==$sCryptograph;
			default:
				return $et($sCleartext)==$sCryptograph;
		}
	}
	private function encodePassword_($sPassword){
		$et=$this->_arrSettings['encode_type'];
		if(is_array($et)){
			return call_user_func($et,$sPassword);
		}
		if($et=='cleartext'){
			return $sPassword;
		}
		if($et=='authcode'){
			$sRandom=G::randString($this->_arrSettings['authcode_random']);
			$this->_arrSavedState['authcode_random']=$sRandom;
			return md5(md5(trim($sPassword)).trim($sRandom));
		}
		return $et($sPassword);
	}
	public function sendCookie($nUserId,$sPassword){
		Dyhb::cookie($GLOBALS['_commonConfig_']['RBAC_DATA_PREFIX'].'auth',
			G::authcode("{$nUserId}\t{$sPassword}",FALSE,NULL,$this->_arrSettings['rbac_login_life']),
			$this->_arrSettings['rbac_login_life']
		);
	}
	public function updateSession($sHash,$nUserId,$sAuthKey,$bChangeUserid=false){
		if($bChangeUserid===true){
			$oSession=SessionModel::F('session_hash=?',$sHash)->getOne();
		}else{
			$oSession=SessionModel::F('user_id=?',$nUserId)->getOne();
		}
		if(!empty($oSession['session_hash'])){
			$oSession->session_hash=$sHash;
			$oSession->user_id=$nUserId;
			$oSession->save(0,'update');
		}else{
			$this->replaceSession($sHash,$nUserId,$sAuthKey,TRUE);// 写入Session数据
		}
	}
	public function replaceSession($sHash,$nUserId,$sAuthKey,$bInsert=false){
		if($nUserId===''){
			return false;
		}
		$oSession=SessionModel::M()->deleteWhere("`session_hash`='$sHash' OR($nUserId<>0 AND `user_id`=$nUserId OR(`user_id`=0))");// 删除SESSION
		if($bInsert){// 新插入Session数据
			$oSession=new SessionModel();
			$oSession->session_hash=$sHash;
			$oSession->session_auth_key=$sAuthKey;
			$oSession->user_id=$nUserId;
			$oSession->save(0);
		}
	}
	public function getTopMenuList(){
		$arrMenuList=array();
		
		$arrMenuList=NodegroupModel::F('nodegroup_status=?',1)
			->order('`nodegroup_sort` ASC')
			->setColumns('nodegroup_id,nodegroup_title')
			->all()
			->asArray()
			->query();
		return $arrMenuList;
	}
	public function getMenuList(){
		$arrMenuList=array();
		$nId=NodeModel::F()->getColumn('node_id');
		$arrWhere['node_level']=2;
		$arrWhere['node_status']=1;
		$arrWhere['node_parentid']=$nId;
		$arrMenuList=NodeModel::F()
			->setColumns('node_id,node_name,nodegroup_id,node_title')
			->order('`node_sort` ASC')
			->all()
			->where($arrWhere)
			->asArray()
			->query();
		$arrAccessList=Dyhb::cookie('_access_list_');
		foreach($arrMenuList as $sKey=>$arrModule){
			if(Dyhb::cookie(md5($GLOBALS['_commonConfig_']['ADMIN_AUTH_KEY'])) OR (is_array($arrAccessList) && isset($arrAccessList[strtolower(APP_NAME)][strtolower($arrModule['node_name'])]))){
				$arrModule['node_access']=1;
				$arrMenuList[$sKey]=$arrModule;
			}
		}
		return $arrMenuList;
	}
	public function saveAccessList($nAuthId=null){
		if(null===$nAuthId){
			$nAuthId=Dyhb::cookie(md5($GLOBALS['_commonConfig_']['USER_AUTH_KEY']));
		}
		if($GLOBALS['_commonConfig_']['USER_AUTH_TYPE']!=2 and !Dyhb::cookie(md5($GLOBALS['_commonConfig_']['ADMIN_AUTH_KEY']))){
			Dyhb::cookie('_access_list_',$this->getAccessList($nAuthId),$this->_arrSettings['rbac_login_life']);
		}
		return ;
	}
	static function getRecordAcessList($nAuthId=null,$sModule=''){
		if(null===$nAuthId){
			$nAuthId=Dyhb::cookie(md5($GLOBALS['_commonConfig_']['USER_AUTH_KEY']));
		}
		if(empty($sModule)){
			$sModule=MODULE_NAME;
		}
		$arrAccessList=$this->getModuleAccessList($nAuthId,$sModule);
		return $arrAccessList;
	}
	public function checkAccess(){
		if($GLOBALS['_commonConfig_']['USER_AUTH_ON']){// 如果项目要求认证，并且当前模块需要认证，则进行权限认证
			$arrModule=array();
			$arrAction=array();
			if(''!=$GLOBALS['_commonConfig_']['REQUIRE_AUTH_MODULE']){
				$arrModule['yes']=Dyhb::normalize(strtoupper($GLOBALS['_commonConfig_']['REQUIRE_AUTH_MODULE']));
			}else{
				$arrModule['no']=Dyhb::normalize(strtoupper($GLOBALS['_commonConfig_']['NOT_AUTH_MODULE']));
			}
			// 检查当前模块是否需要认证
			if((!empty($arrModule['no']) and !in_array(strtoupper(MODULE_NAME),$arrModule['no'])) ||
				(!empty($arrModule['yes']) and in_array(strtoupper(MODULE_NAME),$arrModule['yes'])) || empty($arrModule['yes'])
			){
				if(''!=$GLOBALS['_commonConfig_']['REQUIRE_AUTH_ACTION']){
					$arrAction['yes']=Dyhb::normalize(strtoupper($GLOBALS['_commonConfig_']['REQUIRE_AUTH_ACTION']));// 需要认证的操作
				}else{
					$arrAction['no']=Dyhb::normalize(strtoupper($GLOBALS['_commonConfig_']['NOT_AUTH_ACTION']));// 无需认证的操作
				}
				// 检查当前操作是否需要认证
				if((!empty($arrAction['no']) and !in_array(strtoupper(ACTION_NAME),$arrAction['no'])) ||
					(!empty($arrAction['yes']) and in_array(strtoupper(ACTION_NAME),$arrAction['yes'])) || empty($arrAction['yes'])
				){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		return false;
	}
	public function accessDecision($sAppName=APP_NAME){
		if($this->checkAccess()){
			$sAccessGuid=md5($sAppName.MODULE_NAME.ACTION_NAME);
			$bAdminAuthKey=Dyhb::cookie(md5($GLOBALS['_commonConfig_']['ADMIN_AUTH_KEY']));
			if(empty($bAdminAuthKey)){
				if($GLOBALS['_commonConfig_']['USER_AUTH_TYPE']==2){
					$arrAccessList=$this->getAccessList(Dyhb::cookie(md5($GLOBALS['_commonConfig_']['USER_AUTH_KEY'])));
				}else{
					if($this->getAccessList(Dyhb::cookie($sAccessGuid))){
						return true;
					}
					$arrAccessList=Dyhb::cookie('_access_list_');
				}
				$sLowerAppName=strtolower($sAppName);
				$sLowerModule=MODULE_NAME;
				$sLowerAction=ACTION_NAME;
				if(is_array($arrAccessList) && !isset($arrAccessList[$sLowerAppName][$sLowerAppName.'@'.$sLowerModule][$sLowerAppName.'@'.$sLowerModule.'@'.$sLowerAction])){
					Dyhb::cookie($sAccessGuid,false,$this->_arrSettings['rbac_login_life']);
					return false;
				}else{
					Dyhb::cookie($sAccessGuid,true,$this->_arrSettings['rbac_login_life']);
				}
			}else{
				return true;
			}
		}
		return true;
	}
	public function getAccessList( $nAuthId){
		$oDb=Db::RUN();
		$arrTable=array(
			'role'=>RoleModel::F()->query()->getTablePrefix().$GLOBALS['_commonConfig_']['RBAC_ROLE_TABLE'],
			'userrole'=>UserroleModel::F()->query()->getTablePrefix().$GLOBALS['_commonConfig_']['RBAC_USERROLE_TABLE'],
			'access'=>AccessModel::F()->query()->getTablePrefix().$GLOBALS['_commonConfig_']['RBAC_ACCESS_TABLE'],
			'node'=>NodeModel::F()->query()->getTablePrefix().$GLOBALS['_commonConfig_']['RBAC_NODE_TABLE']
		);
		$sSql="SELECT DISTINCT node.node_id,node.node_name FROM ".
			$arrTable['role']." AS role,".
			$arrTable['userrole']." AS userrole,".
			$arrTable['access']." AS access ,".
			$arrTable['node']." AS node ".
			"WHERE userrole.user_id='{$nAuthId}' and userrole.role_id=role.role_id AND(access.role_id=role.role_id ".
			"OR(access.role_id=role.role_parentid AND role.role_parentid!=0))AND role.role_status=1 AND ".
			"access.node_id=node.node_id AND node.node_level=1 AND node.node_status=1";
		$arrApps=$oDb->getAllRows($sSql);
		$arrAccess=array();// 项目权限列表
		foreach($arrApps as $sKey=>$arrApp){
			$nAppId=$arrApp['node_id'];
			$sAppName=$arrApp['node_name'];
			$arrAccess[ strtolower($sAppName)]=array();// 读取项目的模块权限
			$sSql="SELECT DISTINCT node.node_id,node.node_name FROM ".
				$arrTable['role']." AS role,".
				$arrTable['userrole']." AS userrole,".
				$arrTable['access']." AS access ,".
				$arrTable['node']." AS node ".
				"WHERE userrole.user_id='{$nAuthId}' and userrole.role_id=role.role_id AND(access.role_id=role.role_id ".
				"OR(access.role_id=role.role_parentid AND role.role_parentid!=0))AND role.role_status=1 ".
				"AND access.node_id=node.node_id AND node.node_level=2 AND node.node_parentid={$nAppId} AND node.node_status=1";
			$arrModules=$oDb->getAllRows($sSql);
			$arrPublicAction=array();// 判断是否存在公共模块的权限
			foreach($arrModules as $sKey=>$arrModule){
				$nModuleId=$arrModule['node_id'];
				$sModuleName=$arrModule['node_name'];
				if('PUBLIC'==strtoupper($sModuleName)){
					$sSql="SELECT DISTINCT node.node_id,node.node_name FROM ".
					$arrTable['role']." AS role,".
					$arrTable['userrole']." AS userrole,".
					$arrTable['access']." AS access ,".
					$arrTable['node']." AS node ".
					"WHERE userrole.user_id='{$nAuthId}' AND userrole.role_id=role.role_id AND(access.role_id=role.role_id ".
					" OR(access.role_id=role.role_parentid AND role.role_parentid!=0))AND role.role_status=1 ".
					"AND access.node_id=node.node_id AND node.node_level=3 AND node.node_parentid={$nModuleId} AND node.node_status=1";
					$arrRs=$oDb->getAllRows($sSql);
					foreach($arrRs as $arrA){
						$arrPublicAction[$arrA['node_name']]=$arrA['node_id'];
					}
					unset($arrModules[$sKey]);
					break;
				}
			}
			foreach($arrModules as $sKey=>$arrModule){// 依次读取模块的操作权限
				$nModuleId=$arrModule['node_id'];
				$sModuleName=$arrModule['node_name'];
				$sSql="SELECT DISTINCT node.node_id,node.node_name FROM ".
					$arrTable['role']." AS role,".
					$arrTable['userrole']." AS userrole,".
					$arrTable['access']." AS access ,".
					$arrTable['node']." AS node ".
					"WHERE userrole.user_id='{$nAuthId}' AND userrole.role_id=role.role_id AND(access.role_id=role.role_id ".
					" OR(access.role_id=role.role_parentid AND role.role_parentid!=0))AND role.role_status=1 ".
					" AND access.node_id=node.node_id AND node.node_level=3 and node.node_parentid={$nModuleId} AND node.node_status=1";
				$arrRs=$oDb->getAllRows($sSql);
				$arrAction=array();
				foreach($arrRs as $arrA){
					$arrAction[$arrA['node_name']]=$arrA['node_id'];
				}
				$arrAction+=$arrPublicAction;// 和公共模块的操作权限合并
				$arrAccess[strtolower($sAppName)][strtolower($sModuleName)]=array_change_key_case($arrAction,CASE_LOWER);
			}
		}
		return $arrAccess;
	}
	public function getModuleAccessList($nAuthId,$sModule){
		$oDb=Db::RUN();
		$arrTable=array(
			'role'=>RoleModel::F()->query()->getTablePrefix().$GLOBALS['_commonConfig_']['RBAC_ROLE_TABLE'],
			'userrole'=>UserroleModel::F()->query()->getTablePrefix().$GLOBALS['_commonConfig_']['RBAC_USERROLE_TABLE'],
			'access'=>AccessModel::F()->query()->getTablePrefix().$GLOBALS['_commonConfig_']['RBAC_ACCESS_TABLE'],
		);
		$sSql="SELECT DISTINCT access.node_id FROM ".
			$arrTable['role']." AS role,".
			$arrTable['userrole']." AS userrole,".
			$arrTable['access']." AS access ".
			"WHERE userrole.user_id='{$nAuthId}' AND userrole.role_id=role.role_id AND(access.role_id=role.role_id ".
			" OR(access.role_id=role.role_parentid AND role.role_parentid!=0))AND role.role_status=1 ".
			"AND  access.access_module='{$sModule}' AND access.access_status=1";
		$arrNodes=$oDb->getAllRows($sSql);
		$arrAccess=array();
		foreach($arrNodes as $arrNode){
			$arrAccess[]=$arrNodes['node_id'];
		}
		return $arrAccess;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   SQL表达式封装($)*/
class DbExpression{
	protected $_sExpr;
	public function __construct($sExpr){
		$this->_sExpr=$sExpr;
	}
	public function __toString(){
		return $this->_sExpr;
	}
	public function makeSql($oConnect,$sTableName=null,array $arrMapping=null,$hCallback=null){
		if(!is_array($arrMapping)){
			$arrMapping=array();
		}
		return $oConnect->qualifySql($this->_sExpr,$sTableName,$arrMapping,$hCallback);
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   SQL Select 子句($)*/
class DbSelect{
	const DISTINCT='distinct';
	const COLUMNS='columns';
	const FROM='from';
	const UNION='union';
	const WHERE='where';
	const GROUP='group';
	const HAVING='having';
	const ORDER='order';
	const LIMIT_COUNT='limitcount';
	const LIMIT_OFFSET='limitoffset';
	const LIMIT_QUERY='limitquery';
	const FOR_UPDATE='forupdate';
	const AGGREGATE='aggregate';
	const USED_LINKS='used_links';
	const NON_LAZY_QUERY='non_lazy_query';
	const AS_ARRAY='as_array';
	const AS_COLL='as_coll';
	const LINK_FOR_RECURSION='link_for_recursion';
	const PAGE_SIZE='page_size';
	const PAGE_BASE='page_base';
	const CURRENT_PAGE='current_page';
	const PAGED_QUERY='paged_query';
	const INNER_JOIN='inner join';
	const LEFT_JOIN='left join';
	const RIGHT_JOIN='right join';
	const FULL_JOIN='full join';
	const CROSS_JOIN='cross join';
	const NATURAL_JOIN='natural join';
	const RECURSION='recursion';
	const SQL_WILDCARD='*';
	const SQL_SELECT='SELECT';
	const SQL_UNION='UNION';
	const SQL_UNION_ALL='UNION ALL';
	const SQL_FROM='FROM';
	const SQL_WHERE='WHERE';
	const SQL_DISTINCT='DISTINCT';
	const SQL_GROUP_BY='GROUP BY';
	const SQL_ORDER_BY='ORDER BY';
	const SQL_HAVING='HAVING';
	const SQL_FOR_UPDATE='FOR UPDATE';
	const SQL_AND='AND';
	const SQL_AS='AS';
	const SQL_OR='OR';
	const SQL_ON='ON';
	const SQL_ASC='ASC';
	const SQL_DESC='DESC';
	const SQL_COUNT='COUNT';
	const SQL_MAX='MAX';
	const SQL_MIN='MIN';
	const SQL_AVG='AVG';
	const SQL_SUM='SUM';
	protected static $_arrOptionsInit=array(
		self::DISTINCT=>false,
		self::COLUMNS=>array(),
		self::AGGREGATE=>array(),
		self::UNION=>array(),
		self::FROM=>array(),
		self::WHERE=>null,
		self::GROUP=>array(),
		self::HAVING=>null,
		self::ORDER=>array(),
		self::LIMIT_COUNT=>1,
		self::LIMIT_OFFSET=>null,
		self::LIMIT_QUERY=>false,
		self::FOR_UPDATE=>false
	);
	protected static $_arrAggregateTypes=array(
		self::SQL_COUNT=>self::SQL_COUNT,
		self::SQL_MAX=>self::SQL_MAX,
		self::SQL_MIN=>self::SQL_MIN,
		self::SQL_AVG=>self::SQL_AVG,
		self::SQL_SUM=>self::SQL_SUM
	);
	protected static $_arrJoinTypes=array(
		self::INNER_JOIN=>self::INNER_JOIN,
		self::LEFT_JOIN=>self::LEFT_JOIN,
		self::RIGHT_JOIN=>self::RIGHT_JOIN,
		self::FULL_JOIN=>self::FULL_JOIN,
		self::CROSS_JOIN=>self::CROSS_JOIN,
		self::NATURAL_JOIN=>self::NATURAL_JOIN
	);
	protected static $_arrUnionTypes=array(
		self::SQL_UNION=>self::SQL_UNION,
		self::SQL_UNION_ALL=>self::SQL_UNION_ALL
	);
	protected static $_arrQueryParamsInit=array(
		self::USED_LINKS=>array(),
		self::NON_LAZY_QUERY=>array(),
		self::AS_ARRAY=>true,
		self::AS_COLL=>false,
		self::RECURSION=>1,
		self::LINK_FOR_RECURSION=>null,
		self::PAGED_QUERY=>false
	);
	protected $_arrOptions=array();
	protected $_arrQueryParams;
	protected $_currentTable;
	protected $_arrJoinedTables=array();
	protected $_arrColumnsMapping=array();
	private static $_nQueryId=0;
	protected $_arrLinks=array();
	protected $_oMeta;
	protected $_bForUpdate=false;
	private $_oConnect;
	private $_oSubSqlGroup=null;
	private $_sSubSqlGroup=null;
	private $_oSubSqlReturnColumnList=null;
	private $_sSubSqlReturnColumnList=null;
	private $_oSubSqlOn=null;
	private $_sSubSqlOn=null;
	protected $_sLastSql='';
	protected $_bIsError=false;
	public function __construct(DbConnect $oConnect=null){
		$this->_oConnect=$oConnect;// 初始化数据
		self::$_nQueryId ++;
		$this->_arrOptions=self::$_arrOptionsInit;
		$this->_arrQueryParams=self::$_arrQueryParamsInit;
	}
	public function setConnect(DbConnect $oConnect){
		$this->_oConnect=$oConnect;
		return $this;
	}
	public function getConnect(){
		return $this->_oConnect;
	}
	public function getLastSql(){
		return $this->_sLastSql;
	}
	public function getCounts($Field='*',$sAlias='row_count'){
		$arrRow=$this->count($Field,$sAlias)->query();
		return $arrRow[$sAlias];
	}
	public function getAvg($Field,$sAlias='avg_value'){
		$arrRow=$this->avg($Field,$sAlias)->query();
		return $arrRow[$sAlias];
	}
	public function getMax($Field,$sAlias='max_value'){
		$arrRow=$this->max($Field,$sAlias)->query();
		return $arrRow[$sAlias];
	}
	public function getMin($Field,$sAlias='min_value'){
		$arrRow=$this->min($Field,$sAlias)->query();
		return $arrRow[$sAlias];
	}
	public function getSum($Field,$sAlias='sum_value'){
		$arrRow=$this->sum($Field,$sAlias)->query();
		return $arrRow[$sAlias];
	}
	public function get($nNum=null,$IncludedLinks=null){
		if(!is_null($nNum)){
			return $this->top($nNum)->query($IncludedLinks);
		}else{
			return $this->query($IncludedLinks);
		}
	}
	public function getById($Id,$IncludedLinks=null){
		if($this->_oMeta->_nIdNameCount !=1){
			Dyhb::E(Dyhb::L('getById 方法只适用于单一主键模型' ,'__DYHB__@DbDyhb'));
		}
		return $this->where(array(reset($this->_oMeta->_sIdName)=>$Id))->getOne($IncludedLinks);
	}
	public function getOne($IncludedLinks=null){
		return $this->one()->query($IncludedLinks);
	}
	public function getAll($IncludedLinks=null){
		if($this->_arrOptions[self::LIMIT_QUERY]){
			return $this->query($IncludedLinks);
		}else{
			return $this->all()->query($IncludedLinks);
		}
	}
	public function getColumn($sColumn,$sSepa='-'){
		if(strpos($sColumn,',')){// 多个字段
			$this->all();
		}
		$this->setColumns($sColumn);
		$hHandle=$this->getQueryHandle();
		if($hHandle===false){
			return false;
		}
		return $hHandle->getColumn($sColumn,$sSepa);
	}
	public function query($arrIncludedLinks=null){
		$this->_arrQueryParams[self::NON_LAZY_QUERY]=Dyhb::normalize($arrIncludedLinks);
		if($this->_arrQueryParams[self::AS_ARRAY]){
			return $this->queryArray_(true);
		}else{
			return $this->queryObjects_();
		}
	}
	public function getQueryHandle(){
		$sSql=$this->makeSql();// 构造查询 SQL，并取得查询中用到的关联
		$nOffset=$this->_arrOptions[self::LIMIT_OFFSET];
		$nCount=$this->_arrOptions[self::LIMIT_COUNT];
		if(is_null($nOffset)&& is_null($nCount)){
			$result=$this->_oConnect->exec($sSql);
			return $result;
		}else{
			$result=$this->_oConnect->selectLimit($sSql,$nOffset,$nCount);
			return $result;
		}
	}
	public function __call($sMethod,array $arrArgs){
		if(strncasecmp($sMethod,'get',3)===0){
			$sMethod=substr($sMethod,3);
			if(strpos(strtolower($sMethod),'start')!==false){//support get10start3 etc.
				$arrValue=explode('start',strtolower($sMethod));
				$nNum=intval(array_shift($arrValue));
				$nOffset=intval(array_shift($arrValue));
				return $this->limit($nOffset - 1,$nNum);
			}elseif(strncasecmp($sMethod,'By',2)===0){// support getByName getByNameAndSex etc.
				$sMethod=substr($sMethod,2);
				$arrKeys=explode('And',$sMethod);
				if(count($arrKeys)!=count($arrArgs)){
					Dyhb::E(Dyhb::L('参数数量不对应','__DYHB__@DbDyhb'));
				}
				return $this->where(array_change_key_case(array_combine($arrKeys,$arrArgs),CASE_LOWER))->getOne();
			}elseif(strncasecmp($sMethod,'AllBy',5)===0){// support getAllByNameAndSex etc.
				$sMethod=substr($sMethod,5);
				$arrKeys=explode('And',$sMethod);
				if(count($arrKeys)!=count($arrArgs)){
					Dyhb::E(Dyhb::L('参数数量不对应','__DYHB__@DbDyhb'));
				}
				return $this->where(array_change_key_case(array_combine($arrKeys,$arrArgs),CASE_LOWER))->getAll();
			}
			return $this->top(intval(substr($sMethod,3)));
		}elseif(method_exists($this->_oMeta->_sClassName,'find_'.$sMethod)){// ArticleModel::F()->hot()->getOne()	,static method `find_on_hot` must define in ArticleModel
			array_unshift($arrArgs,$this);
			return call_user_func_array(array($this->_oMeta->_sClassName,'find_'.$sMethod),$arrArgs);
		}
		Dyhb::E(Dyhb::L('DbSelect 没有实现魔法方法 %s.','__DYHB__@DbDyhb',null,$sMethod));
	}
	protected function queryArray_($bCleanUp=true){
		$hHandle=$this->getQueryHandle();
		if($hHandle===false){
			return false;
		}
		if($this->_arrQueryParams[self::RECURSION]>0 && $this->_arrQueryParams[self::USED_LINKS]){
			$arrRefsValue=null;// 对关联表进行查询，并组装数据
			$arrRefs=null;
			$arrUsedAlias=array_keys($this->_arrQueryParams[self::USED_LINKS]);
			$arrRowset=$hHandle->fetchAllRefby($arrUsedAlias,$arrRefsValue,$arrRefs,$bCleanUp);
			$arrKeys=array_keys($arrRowset);
			foreach($this->_arrQueryParams[self::USED_LINKS] as $oLink){// 进行关联查询，并组装数据集
				foreach($arrKeys as $sKey){// ModelRelation
					$arrRowset[$sKey][$oLink->_sMappingName]=$oLink->_sOneToOne ?null :array();
				}
				$sTka=$oLink->_sTargetKeyAlias;
				$sSka=$oLink->_sSourceKeyAlias;
				if(empty($arrRefsValue[$sSka])){
					continue;
				}
				$oSelect=$oLink->_sTargetTable
							->find("[{$oLink->_sTargetKey}] IN(?)",$arrRefsValue[$sSka])
							->recursion($this->_arrQueryParams[self::RECURSION] - 1)
							->linkForRecursion($oLink)
							->order($oLink->_sOnFindOrder)
							->select($oLink->_onFindKeys)
							->where($oLink->_onFindWhere);
				if($oLink->_sType==Db::MANY_TO_MANY){
					$oSelect->join($oLink->_sMidTable->_sName,"[{$oLink->_sMidTargetKey}]=[{$oLink->_sTargetKey}]");
				}
				if(is_int($oLink->_onFind)){
					$oSelect->limit(0,$oLink->_onFind);
				}elseif(is_array($oLink->_onFind)){
					$oSelect->limit($oLink->_onFind[0],$oLink->_onFind[1]);
				}else{
					$oSelect->all();
				}
				$arrTargetRowset=$oSelect->queryArray(false);
				if($oLink->_sOnFind===1){
					$arrTargetRowset=array(
						$arrTargetRowset
					);
				}
				if($oLink->_sOneToOne){// 组装数据集
					foreach(array_keys($arrTargetRowset)as $offset){
						$sV=$arrTargetRowset[$offset][$sTka];
						unset($arrTargetRowset[$offset][$sTka]);
						$nI=0;
						foreach($arrRefs[$sTka][$sV] as $arrRow){
							$refs[$sTka][$sV][$nI][$oLink->_sMappingName]=$arrTargetRowset[$offset];
							unset($refs[$sTka][$sV][$nI][$sSka]);
							$nI ++;
						}
					}
				}else{
					foreach(array_keys($arrTargetRowset)as $offset){
						$sV=$arrTargetRowset[$offset][$sTka];
						unset($arrTargetRowset[$offset][$sTka]);
						$nI=0;
						foreach($arrRefs[$sSka][$sV] as $arrRow){
							$arrRefs[$sSka][$sV][$nI][$oLink->_sMappingName][]=$arrTargetRowset[$offset];
							unset($arrRefs[$sSka][$sV][$nI][$sSka]);
						}
					}
				}
			}
			unset($arrRefs);
			unset($arrRefsValue);
			unset($arrRow);
			if($this->limit==1){
				$arrRow=reset($arrRowset);
				unset($arrRowset);
			}
		}else{
			// 非关联查询
			if(isset($arrRow)){
				unset($arrRow);
			}
			if(isset($arrRowset)){
				unset($arrRowset);
			}
			if($this->_arrOptions[self::LIMIT_COUNT]==1){
				$arrRow=$hHandle->fetch();
			}else{
				$arrRowset=$hHandle->getAllRows();
			}
		}
		if(count($this->_arrOptions[self::AGGREGATE])&& isset($arrRowset)){
			if(empty($this->_arrOptions[self::GROUP])){
				return reset($arrRowset);
			}else{
				return $arrRowset;
			}
		}
		if(isset($arrRow)){
			return $arrRow;
		}else{
			if(!isset($arrRowset)){
				$arrRowset=array();
			}
			return $arrRowset;
		}
	}
	protected function queryObjects_(){
		// 执行查询，获得一个查询句柄&$this->_query_params[self::USED_LINKS] 是查询涉及到的关联（关联别名=>关联对象）
		$hHandle=$this->getQueryHandle();
		if($hHandle===false){
			return false;
		}
		$sClassName=$this->_oMeta->_sClassName;
		$arrRowset=array();
		$this->_arrQueryParams[self::USED_LINKS]=$this->_arrQueryParams[self::USED_LINKS];
		$arrNoLazyQuery=Dyhb::normalize($this->_arrQueryParams[self::NON_LAZY_QUERY]);
		while(($arrRow=$hHandle->fetch())!==false){
			if($this->_oMeta->_sInheritTypeField){
				$sClassName=$arrRow[$this->_oMeta->_sInheritTypeField ];
			}
			$oObj=new $sClassName($arrRow,Db::FIELD,true);
			if(is_array($arrNoLazyQuery)){
				foreach($arrNoLazyQuery as $sAssoc){
					$oObj->{$sAssoc};
				}
			}
			$arrRowset[]=$oObj;
		}
		if(empty($arrRowset)){
			if(! $this->_arrOptions[self::LIMIT_QUERY]){// 没有查询到数据时，返回 Null 对象或空集合
				return $this->_oMeta->newObj();
			}else{
				if($this->_arrQueryParams[self::AS_COLL]){
					return new ModelRelationColl($this->_oMeta->_sClassName);
				}else{
					return array();
				}
			}
		}
		if(!$this->_arrOptions[self::LIMIT_QUERY]){
			return reset($arrRowset);// 创建一个单独的对象
		}else{
			if($this->_arrQueryParams[self::AS_COLL]){
				return ModelRelationColl::createFromArray($arrRowset,$this->_oMeta->_sClassName);
			}else{
				return $arrRowset;
			}
		}
	}
	public function isError(){
		return $this->_bIsError;
	}
	public function setIsError($isError=true){
		$bOldValue=$this->_bIsError;
		$this->_bIsError=$isError;
		return $bOldValue;
	}
	public function getErrorMessage(){
		return $this->_sErrorMessage;
	}
	public function setErrorMessage($sMessage=''){
		$sOldValue=$this->_sErrorMessage;
		$this->_sErrorMessage=$sMessage;
		$this->setIsError(true);
		return $sOldValue;
	}
	public function distinct($bFlag=true){
		$this->_arrOptions[self::DISTINCT]=(bool)$bFlag;
		return $this;
	}
	public function from($Table,$Cols=self::SQL_WILDCARD){
		$this->_currentTable=$Table;
		return $this->join_(self::INNER_JOIN,$Table,$Cols);
	}
	public function columns($Cols=self::SQL_WILDCARD,$Table=null){
		if(is_null($Table)){
			$Table=$this->getCurrentTableName_();
		}
		$this->addCols_($Table,$Cols);
		return $this;
	}
	public function setColumns($Cols=self::SQL_WILDCARD,$Table=null){
		if(is_null($Table)){
			$Table=$this->getCurrentTableName_();
		}
		$this->_arrOptions[self::COLUMNS]=array();
		$this->addCols_($Table,$Cols);
		return $this;
	 }
	public function where($Cond /* args */){
		$arrArgs=func_get_args();
		array_shift($arrArgs);
		return $this->addConditions_($Cond,$arrArgs,self::WHERE,true);
	}
	public function orWhere($Cond /* args */){
		$arrArgs=func_get_args();
		array_shift($arrArgs);
		return $this->addConditions_($Cond,$arrArgs,self::WHERE,false);
	}
	public function link($Link){
		if(! is_array($Link)){
			$arrLinks=array($Link);
		}else{
			$arrLinks=$Link;
		}
		foreach($arrLinks as $Link){
			if($Link instanceof ModelRelation){
				$this->_arrLinks[$Link->_sMappingName]=$Link;
			}else{
				Dyhb::E(Dyhb::L('关联必须是 DbActiveRecordAssociation 类型' ,'__DYHB__@DbDyhb'));
			}
		}
		return $this;
	}
	public function join($Table,$Cols=self::SQL_WILDCARD,$Cond /* args */){
		$arrArgs=func_get_args();
		return $this->join_(self::INNER_JOIN,$Table,$Cols,$Cond,array_slice($arrArgs,3));
	}
	public function joinInner($Table,$Cols=self::SQL_WILDCARD,$Cond){
		$arrArgs=func_get_args();
		return $this->join_(self::INNER_JOIN,$Table,$Cols,$Cond,array_slice($arrArgs,3));
	}
	public function joinLeft($Table,$Cols=self::SQL_WILDCARD,$Cond){
		$arrArgs=func_get_args();
		return $this->join_(self::LEFT_JOIN,$Table,$Cols,$Cond,array_slice($arrArgs,3));
	}
	public function joinRight($Table,$Cols=self::SQL_WILDCARD,$Cond){
		$arrArgs=func_get_args();
		return $this->join_(self::RIGHT_JOIN,$Table,$Cols,$Cond,array_slice($arrArgs,3));
	}
	public function joinFull($Table,$Cols=self::SQL_WILDCARD,$Cond){
		$arrArgs=func_get_args();
		return $this->join_(self::FULL_JOIN,$Table,$Cols,$Cond,array_slice($arrArgs,3));
	}
	public function joinCross($Table,$Cols=self::SQL_WILDCARD){
		return $this->join_(self::CROSS_JOIN,$Table,$Cols);
	}
	public function joinNatural($Table,$Cols=self::SQL_WILDCARD){
		return $this->join_(self::NATURAL_JOIN,$Table,$Cols);
	}
	public function union($Select=array(),$sType=self::SQL_UNION){
		if(! is_array($Select)){
			$Select=array($Select);
		}
		if(!isset(self::$_arrUnionTypes[$sType])){
			Dyhb::E(Dyhb::L('无效的 UNION 类型 %s','__DYHB__@DbDyhb',null,$sType));
		}
		foreach($Select as $Target){
			$this->_arrOptions[self::UNION][]=array($Target,$sType);
		}
		return $this;
	}
	public function group($Expr){
		if(!is_array($Expr)){// 表达式
			$Expr=array($Expr);
		}
		foreach($Expr as $Part){
			if($Part instanceof DbExpression){
				$Part=$Part->makeSql($this->_oConnect,$this->getCurrentTableName_(),$this->_arrColumnsMapping);
			}else{
				$Part=$this->_oConnect->qualifySql($Part,$this->getCurrentTableName_(),$this->_arrColumnsMapping);
			}
			$this->_arrOptions[self::GROUP][]=$Part;
		}
		return $this;
	}
	public function having($Cond /* args */){
		$arrArgs=func_get_args();
		array_shift($arrArgs);
		return $this->addConditions_($Cond,$arrArgs,self::HAVING,true);
	}
	public function orHaving($Cond){
		$arrArgs=func_get_args();
		array_shift($arrArgs);
		return $this->addConditions_($Cond,$arrArgs,self::HAVING,false);
	}
	public function order($Expr){
		if(!is_array($Expr)){// 非数组
			$Expr=array($Expr);
		}
		$arrM=null;
		foreach($Expr as $Val){
			if($Val instanceof DbExpression){
				$Val=$Val->makeSql($this->_oConnect,$this->getCurrentTableName_(),$this->_arrColumnsMapping);
				if(preg_match('/(.*\W)('.self::SQL_ASC.'|'.self::SQL_DESC.')\b/si',$Val,$arrM)){
					$Val=trim($arrM[1]);
					$sDir=$arrM[2];
				}else{
					$sDir=self::SQL_ASC;
				}
				$this->_arrOptions[self::ORDER][]=$Val.' '.$sDir;
			}else{
				$arrCols=explode(',',$Val);
				foreach($arrCols as $Val){
					$Val=trim($Val);
					if(empty($Val)){
						continue;
					}
					$sCurrentTableName=$this->getCurrentTableName_();
					$sDir=self::SQL_ASC;
					$arrM=null;
					if(preg_match('/(.*\W)('.self::SQL_ASC.'|'.self::SQL_DESC.')\b/si',$Val,$arrM)){
						$Val=trim($arrM[1]);
						$sDir=$arrM[2];
					}
					if(!preg_match('/\(.*\)/',$Val)){
						if(preg_match('/(.+)\.(.+)/',$Val,$arrM)){
							$sCurrentTableName=$arrM[1];
							$Val=$arrM[2];
						}
						if(isset($this->_arrColumnsMapping[$Val])){
							$Val=$this->_arrColumnsMapping[$Val];
						}
						$Val=$this->_oConnect->qualifyId("{$sCurrentTableName}.{$Val}");
					}
					$this->_arrOptions[self::ORDER][]=$Val.' '.$sDir;
				}
			}
		}
		return $this;
	}
	public function one(){
		$this->_arrOptions[self::LIMIT_COUNT]=1;
		$this->_arrOptions[self::LIMIT_OFFSET]=null;
		$this->_arrOptions[self::LIMIT_QUERY]=false;
		return $this;
	}
	public function all(){
		$this->_arrOptions[self::LIMIT_COUNT]=null;
		$this->_arrOptions[self::LIMIT_OFFSET]=null;
		$this->_arrOptions[self::LIMIT_QUERY]=true;
		return $this;
	}
	public function limit($nOffset=0,$nCount=30){
		$this->_arrOptions[self::LIMIT_COUNT]=abs(intval($nCount));
		$this->_arrOptions[self::LIMIT_OFFSET]=abs(intval($nOffset));
		$this->_arrOptions[self::LIMIT_QUERY]=true;
		return $this;
	}
	public function top($nCount=30){
		return $this->limit(0,$nCount);
	}
	public function forUpdate($bFlag=true){
		$this->_bForUpdate=(bool)$bFlag;
		return $this;
	}
	public function count($Field='*',$sAlias='row_count'){
		return $this->addAggregate_(self::SQL_COUNT,$Field,$sAlias);
	}
	public function avg($Field,$sAlias='avg_value'){
		return $this->addAggregate_(self::SQL_AVG,$Field,$sAlias);
	}
	public function max($Field,$sAlias='max_value'){
		return $this->addAggregate_(self::SQL_MAX,$Field,$sAlias);
	}
	public function min($Field,$sAlias='min_value'){
		return $this->addAggregate_(self::SQL_MIN,$Field,$sAlias);
	}
	public function sum($Field,$sAlias='sum_value'){
		return $this->addAggregate_(self::SQL_SUM,$Field,$sAlias);
	}
	public function asObj($sClassName){
		$this->_oMeta=ModelMeta::instance($sClassName);
		$this->_arrQueryParams[self::AS_ARRAY]=false;
		return $this;
	}
	public function asArray(){
		$this->_oMeta=null;
		$this->_arrQueryParams[self::AS_ARRAY]=true;
		return $this;
	}
	public function asColl($bAsColl=true){
		$this->_arrQueryParams[self::AS_COLL]=$bAsColl;
		return $this;
	}
	public function columnMapping($Name,$sMappingTo=NULL){
		if(is_array($Name)){
			$this->_arrColumnsMapping=array_merge($this->_arrColumnsMapping,$Name);
		}else{
			if(empty($sMappingTo)){
				unset($this->_arrColumnsMapping[$Name]);
			}else{
				$this->_arrColumnsMapping[$Name]=$sMappingTo;
			}
		}
		return $this;
	}
	public function recursion($nRecursion){
		$this->_arrQueryParams[self::RECURSION]=abs(intval($nRecursion));
		return $this;
	}
	public function linkForRecursion(ModelRelation $oLink){
		$this->_arrQueryParams[self::LINK_FOR_RECURSION]=$oLink;
		return $this;
	}
	public function getOption($sOption){
		$sOption=strtolower($sOption);
		if(!array_key_exists($sOption,$this->_arrOptions)){
			Dyhb::E(Dyhb::L('无效的部分名称 %s' ,'__DYHB__@DbDyhb',null,$sOption));
		}
		return $this->_arrOptions[$sOption];
	}
	public function reset($sOption=null){
		if($sOption==null){// 设置整个配置
			$this->_arrOptions=self::$_arrOptionsInit;
			$this->_arrQueryParams=self::$_arrQueryParamsInit;
		}elseif(array_key_exists($sOption,self::$_arrOptionsInit)){
			$this->_arrOptions[$sOption]=self::$_arrOptionsInit[$sOption];
		}
		return $this;
	}
	public function makeSql(){
		$arrSql=array(
			self::SQL_SELECT
		);
		foreach(array_keys(self::$_arrOptionsInit)as $sOption){
			if($sOption==self::FROM){
				$arrSql[self::FROM]='';
			}else{
				$sMethod='parse'.ucfirst($sOption).'_';
				if(method_exists($this,$sMethod)){
					$arrSql[$sOption]=$this->$sMethod();
				}
			}
		}
		$arrSql[self::FROM]=$this->parseFrom_();
		foreach($arrSql as $nOffset=>$sOption){// 删除空元素
			if(trim($sOption)==''){
				unset($arrSql[$nOffset]);
			}
		}
		$this->_sLastSql=implode(' ',$arrSql);
		return $this->_sLastSql;
	}
	protected function parseDistinct_(){
		if($this->_arrOptions[self::DISTINCT]){
			return self::SQL_DISTINCT;
		}else{
			return '';
		}
	}
	protected function parseColumns_(){
		if(empty($this->_arrOptions[self::COLUMNS])){
			return '';
		}
		if($this->_arrQueryParams[self::PAGED_QUERY]){
			return 'COUNT(*)';
		}
		$arrColumns=array();// $this->_arrOptions[self::COLUMNS] 每个元素的格式
		foreach($this->_arrOptions[self::COLUMNS] as $arrEntry){
			list($sTableName,$Col,$sAlias)=$arrEntry;// array($currentTableName,$Col,$sAlias | null)
			if($Col instanceof DbExpression){// $Col 是一个字段名或者一个 DbExpression 对象
				$arrColumns[]=$Col->makeSql($this->_oConnect,$sTableName,$this->_arrColumnsMapping);
			}else{
				if(isset($this->_arrColumnsMapping[$Col])){
					$Col=$this->_arrColumnsMapping[$Col];
				}
				$Col=$this->_oConnect->qualifyId("{$sTableName}.{$Col}");
				if($Col!=self::SQL_WILDCARD && $sAlias){
					$arrColumns[]=$this->_oConnect->qualifyId($Col,$sAlias,'AS');
				}else{
					$arrColumns[]=$Col;
				}
			}
		}
		if($this->_arrQueryParams[self::RECURSION]>0){// 确定要查询的关联，从而确保查询主表时能够得到相关的关联字段
			foreach($this->_arrLinks as $oLink){
				$oLink->init();
				if(!$oLink->_bEnabled || $oLink->_bOnFind===false || $link->_bOnFind==='skip'){
					continue;
				}
				$oLink->init();
				$arrColumns[]=$this->qualifyId($oLink->_oSourceTable->getConnect(),$oLink->_sSourceKey,$oLink->_sSourceKeyAlias,'AS');
				$this->_arrQueryParams[self::USED_LINKS][$link->_sSourceKeyAlias]=$oLink;
			}
		}
		if($this->_arrQueryParams[self::LINK_FOR_RECURSION]){// 如果指定了来源关联，则需要查询组装数据所需的关联字段
			$oLink=$this->_arrQueryParams[self::LINK_FOR_RECURSION];
			$arrColumns[]=$this->qualifyId($oLink->_oTargetTable->getConnect(),$oLink->_sTargetKey,$link->_sTargetKeyAlias,'AS');
		}
		return implode(',',$arrColumns);
	}
	protected function parseAggregate_(){
		$arrColumns=array();
		foreach($this->_arrOptions[self::AGGREGATE] as $arrAggregate){
			list(,$sField,$sAlias)=$arrAggregate;
			if($sAlias){
				$arrColumns[]=$sField.' AS '.$sAlias;
			}else{
				$arrColumns[]=$sField;
			}
		}
		return(empty($arrColumns))?'':implode(',',$arrColumns);
	}
	protected function parseFrom_(){
		$arrFrom=array();
		foreach($this->_arrOptions[self::FROM] as $sAlias=>$arrTable){
			$sTmp='';
			if(!empty($arrFrom)){// 如果不是第一个 FROM，则添加 JOIN
				$sTmp.=' '.strtoupper($arrTable['join_type']).' ';
			}
			if($sAlias==$arrTable['table_name']){
				$sTmp.=$this->_oConnect->qualifyId("{$arrTable['schema']}.{$arrTable['table_name']}");
			}else{
				$sTmp.=$this->_oConnect->qualifyId("{$arrTable['schema']}.{$arrTable['table_name']}",$sAlias);
			}
			if(!empty($arrFrom) && !empty($arrTable['join_cond'])){// 添加 JOIN 查询条件
				$sTmp.="\n  ".self::SQL_ON.' '.$arrTable['join_cond'];
			}
			$arrFrom[]=$sTmp;
		}
		if(!empty($arrFrom)){
			return "\n ".self::SQL_FROM.' '.implode("\n",$arrFrom);
		}else{
			return '';
		}
	}
	protected function parseUnion_(){
		$sSql='';
		if($this->_arrOptions[self::UNION]){
			$nOptions=count($this->_arrOptions[self::UNION]);
			foreach($this->_arrOptions[self::UNION] as $nCnt=>$arrUnion){
				list($oTarget,$sType)=$arrUnion;
				if($oTarget instanceof DbRecordSet){
					$oTarget=$oTarget->makeSql();
				}
				$sSql.=$oTarget;
				if($nCnt < $nOptions - 1){
					$sSql.=' '.$sType.' ';
				}
			}
		}
		return $sSql;
	}
	protected function parseWhere_(){
		$sSql='';
		if(!empty($this->_arrOptions[self::FROM]) && !is_null($this->_arrOptions[self::WHERE])){
			$sWhere=$this->_arrOptions[self::WHERE]->makeSql($this->_oConnect,$this->getCurrentTableName_(),null,array($this,'parseTableName_'));
			if(!empty($sWhere)){
				$sSql.="\n ".self::SQL_WHERE.' '.$sWhere;
			}
		}
		return $sSql;
	}
	protected function parseGroup_(){
		if(!empty($this->_arrOptions[self::FROM]) && !empty($this->_arrOptions[self::GROUP])){
			return "\n ".self::SQL_GROUP_BY.' '.implode(",\n\t",$this->_arrOptions[self::GROUP]);
		}
		return '';
	}
	protected function parseHaving_(){
		if(!empty($this->_arrOptions[self::FROM]) && !empty($this->_arrOptions[self::HAVING])){
			return "\n ".self::SQL_HAVING.' '.implode(",\n\t",$this->_arrOptions[self::HAVING]);
		}
		return '';
	}
	protected function parseOrder_(){
		if(!empty($this->_arrOptions[self::ORDER])){
			return "\n ".self::SQL_ORDER_BY.' '.implode(',',array_unique($this->_arrOptions[self::ORDER]));
		}
		return '';
	}
	protected function parseForUpdate_(){
		if($this->_arrOptions[self::FOR_UPDATE]){
			return "\n ".self::SQL_FOR_UPDATE;
		}
		return '';
	}
	protected function join_($sJoinType,$Name,$Cols,$Cond=null,$arrCondArgs=null){
		if(!isset(self::$_arrJoinTypes[$sJoinType])){
			Dyhb::E(Dyhb::L('无效的 JOIN 类型 %s','__DYHB__@DbDyhb',null,$sJoinType));
		}
		// 不能在使用 UNION 查询的同时使用 JOIN 查询.
		if(count($this->_arrOptions[self::UNION])){
			Dyhb::E(Dyhb::L('不能在使用 UNION 查询的同时使用 JOIN 查询','__DYHB__@DbDyhb'));
		}
		// 根据 $Name 的不同类型确定数据表名称、别名
		$arrM=array();
		if(empty($Name)){// 没有指定表，获取默认表
			$Table=$this->getCurrentTableName_();
			$sAlias='';
		}elseif(is_array($Name)){// $Name为数组配置
			foreach($Name as $sAlias=>$Table){
				if(!is_string($sAlias)){
					$sAlias='';
				}
				break;
			}
		}elseif($Name instanceof DbTableEnter){// 如果为DbTableEnter的实例
			$Table=$Name;
			$sAlias='';
		}elseif(preg_match('/^(.+)\s+AS\s+(.+)$/i',$Name,$arrM)){// 字符串指定别名
			$Table=$arrM[1];
			$sAlias=$arrM[2];
		}else{
			$Table=$Name;
			$sAlias='';
		}
		// 确定 table_name 和 schema
		if($Table instanceof DbTableEnter){
			$sSchema=$Table->_sSchema;
			$sTableName=$Table->_sPrefix.$Table->_sName;
		}else{
			$arrM=explode('.',$Table);
			if(isset($arrM[1])){
				$sSchema=$arrM[0];
				$sTableName=$arrM[1];
			}else{
				$sSchema=null;
				$sTableName=$Table;
			}
		}
		$sAlias=$this->uniqueAlias_(empty($sAlias)?$sTableName:$sAlias);// 获得一个唯一的别名
		if(!($Cond instanceof DbCond)){// 处理查询条件
			$Cond=DbCond::createByArgs($Cond,$arrCondArgs);
		}
		$sWhereSql=$Cond->makeSql($this->_oConnect,$sAlias,$this->_arrColumnsMapping);
		$this->_arrOptions[self::FROM][$sAlias]=array(// 添加一个要查询的数据表
			'join_type'=>$sJoinType,'table_name'=>$sTableName,'schema'=>$sSchema,'join_cond'=>$sWhereSql
		);
		$this->addCols_($sAlias,$Cols);// 添加查询字段
		return $this;
	}
	protected function addCols_($sTableName,$Cols){
		$Cols=Dyhb::normalize($Cols);
		if(is_null($sTableName)){
			$sTableName='';
		}
		$arrM=null;
		if(is_object($Cols)&&($Cols instanceof DbExpression)){// Cols为对象
			$this->_arrOptions[self::COLUMNS][]=array($sTableName,$Cols,null);
		}else{
			// 没有字段则退出
			if(empty($Cols)){
				return;
			}
			
			foreach($Cols as $sAlias=>$Col){
				if(is_string($Col)){
					foreach(Dyhb::normalize($Col)as $sCol){// 将包含多个字段的字符串打散
						$currentTableName=$sTableName;
						if(preg_match('/^(.+)\s+'.self::SQL_AS.'\s+(.+)$/i',$sCol,$arrM)){// 检查是不是 "字段名 AS 别名"这样的形式
							$sCol=$arrM[1];
							$sAlias=$arrM[2];
						}
						if(preg_match('/(.+)\.(.+)/',$sCol,$arrM)){// 检查字段名是否包含表名称
							$currentTableName=$arrM[1];
							$sCol=$arrM[2];
						}
						if(isset($this->_arrColumnsMapping[$sCol])){
							$sCol=$this->_arrColumnsMapping[$sCol];
						}
						$this->_arrOptions[self::COLUMNS][]=array(
							$currentTableName,$sCol,is_string($sAlias)?$sAlias:null
						);
					}
				}else{
					$this->_arrOptions[self::COLUMNS][]=array($sTableName,$Col,is_string($sAlias)?$sAlias:null);
				}
			}
		}
	}
	protected function addConditions_($Cond,array $arrArgs,$sPartType,$bBool){
		// DbCond对象
		if(!($Cond instanceof DbCond)){
			if(empty($Cond)){
				return $this;
			}
			$Cond=DbCond::createByArgs($Cond,$arrArgs,$bBool);
		}
		// 空，直接创建DbCond
		if(is_null($this->_arrOptions[$sPartType])){
			$this->_arrOptions[$sPartType]=new DbCond();
		}
		if($bBool){// and类型
			$this->_arrOptions[$sPartType]->andCond($Cond);
		}else{// or类型
			$this->_arrOptions[$sPartType]->orCond($Cond);
		}
		return $this;
	}
	protected function getCurrentTableName_(){
		if(is_array($this->_currentTable)){// 数组
			while((list($sAlias,)=each($this->_currentTable))!==false){
				$this->_currentTable=$sAlias;
				return $sAlias;
			}
		}elseif(is_object($this->_currentTable)){
			return $this->_currentTable->_sPrefix.$this->_currentTable->_sName;
		}else{
			return $this->_currentTable;
		}
	}
	public function parseTableName_($sTableName){
		if(strpos($sTableName,'.')!==false){// 获取表模式
			list($sSchema,$sTableName)=explode('.',$sTableName);
		}else{
			$sSchema=null;
		}
		if(is_null($this->_oMeta)|| !isset($this->_oMeta->associations[$sTableName])){
			return $sTableName;
		}
		$oAssoc=$this->_oMeta->assoc($sTableName)->init();
		$oTargetTable=$assoc->_oTargetMeta->_oTable;
		if($sSchema && $oTargetTable->_sSchema && $oTargetTable->_sSchema!=$sSchema){
			return "{$sSchema}.{$sTableName}";
		}
		$sAssocTableName=$oAssoc->_oTargetMeta->_oTable->getFullTableName();
		$sCurrentTableName=$this->getCurrentTableName_();
		switch($oAssoc->_sType){
			case Db::HAS_MANY:
			case Db::HAS_ONE:
			case Db::BELONGS_TO:
				$sKey="{$oAssoc->_sType}-{$sAssocTableName}";
				if(!isset($this->_arrJoinedTables[$sKey])){
					// 支持额外join 条件设定，用于关联查询
					$sJoinCondExtra='';
					if(isset($this->_oMeta->_arrProps[$oAssoc->_sMappingName]['assoc_params']['join_cond_extra'])){
						$sJoinCondExtra=" AND ".trim($this->_oMeta->_arrProps[$assoc->_sMappingName]['assoc_params']['join_cond_extra']);
					}
					$this->joinInner($sAssocTableName,'',"{$sAssocTableName}.{$oAssoc->_sTargetKey}="."{$sCurrentTableName}.{$oAssoc->_sSourceKey}{$sJoinCondExtra}");
					$this->_arrJoinedTables[$sKey]=true;
				}
				break;
			 case Db::MANY_TO_MANY:
				$sMidTableName=$oAssoc->_oMidTable->getFullTableName();
				$sKey="{$oAssoc->_sType}-{$sMidTableName}";
				if(!isset($this->_joined_tables[$sKey])){
					$this->joinInner($sMidTableName,'',"{$sMidTableName}.{$oAssoc->_sMidSourceKey}="."{$sCurrentTableName}.{$oAssoc->_sMidSourceKey}");
					$this->joinInner($sAssocTableName,'',"{$sAssocTableName}.{$oAssoc->_sTargetKey}="."{$sMidTableName}.{$oAssoc->_arrMidTargetKey}");
					$this->_arrJoinedTables[$sKey]=true;
				}
				break;
		}
		return $sAssocTableName;
	}
	protected function addAggregate_($sType,$Field,$sAlias){
		$this->_arrOptions[self::COLUMNS]=array();
		$this->_arrQueryParams[self::RECURSION]=0;
		if($Field instanceof DbExpression){
			$Field=$Field->makeSql($this->_oConnect,$this->getCurrentTableName_(),$this->_arrColumnsMapping);
		}else{
			if(isset($this->_arrColumnsMapping[$Field])){
				$Field=$this->_arrColumnsMapping[$Field];
			}
			$Field=$this->_oConnect->qualifySql($Field,$this->getCurrentTableName_(),$this->_arrColumnsMapping);
			$Field="{$sType}($Field)";
		}
		$this->_arrOptions[self::AGGREGATE][]=array(
			$sType,$Field,$sAlias
		);
		$this->_arrQueryParams[self::AS_ARRAY]=true;
		return $this;
	}
	private function uniqueAlias_($Name){
		if(empty($Name)){
			return '';
		}
		if(is_array($Name)){// 数组，返回最后一个元素
			$sC=end($Name);
		}else{// 字符串
			$nDot=strrpos($Name,'.');
			$sC=($nDot===false)?$Name:substr($Name,$nDot+1);
		}
		for($nI=2; array_key_exists($sC,$this->_arrOptions[self::FROM]);++$nI){
			$sC=$Name.'_'.(string)$nI;
		}
		return $sC;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   DbCond类封装复杂的查询条件($)*/
class DbCond{
	const BEGIN_GROUP='(';
	const END_GROUP=')';
	protected $_arrOptions=array();
	public function __construct(){
		$arrArgs=func_get_args();
		if(!empty($arrArgs)){
			$this->_arrOptions[]=array($arrArgs,true);
		}
	}
	public static function create(){
		$oCond=new DbCond();
		$arrArgs=func_get_args();
		if(!empty($arrArgs)){
			$oCond->appendDirect($arrArgs);
		}
		return $oCond;
	}
	public static function createByArgs($Cond,array $arrCondArgs=null,$bBool=true){
		if(!is_array($arrCondArgs)){
			$arrCondArgs=array();
		}
		$oCond=new DbCond();
		if(!empty($Cond)){
			array_unshift($arrCondArgs,$Cond);
			$oCond->appendDirect($arrCondArgs,$bBool);
		}
		return $oCond;
	}
	public function appendDirect(array $arrArgs,$bBool=true){
		$this->_arrOptions[]=array($arrArgs,$bBool);
		return $this;
	}
	public function andCond(){
		$this->_arrOptions[]=array(func_get_args(),true);
		return $this;
	}
	public function orCond(){
		$this->_arrOptions[]=array(func_get_args(),false);
		return $this;
	}
	public function andGroup(){
		$this->_arrOptions[]=array(self::BEGIN_GROUP,true);
		$this->_arrOptions[]=array(func_get_args(),true);
		return $this;
	}
	public function orGroup(){
		$this->_arrOptions[]=array(self::BEGIN_GROUP,false);
		$this->_arrOptions[]=array(func_get_args(),false);
		return $this;
	}
	public function endGroup(){
		$this->_arrOptions[]=array(self::END_GROUP,null);
		return $this;
	}
	public function makeSql($oConnect,$sTableName=null,array $arrFieldsMapping=null,$hCallback=null){
		if(empty($this->_arrOptions)){
			return '';
		}
		if(is_null($arrFieldsMapping)){
			$arrFieldsMapping=array();
		}
		$sSql='';
		$bSkipCondLink=true;
		$bBool=true;
		$arrBigSql=array();
		// $this->_arrOptions 的存储结构是一个二维数组
		// 数组的每一项如下：
		// - 要处理的查询条件
		// - 该查询条件与其他查询条件是 AND 还是 OR 关系
		foreach($this->_arrOptions as $arrOption){
			list($arrArgs,$bBoolItem)=$arrOption;
			if(empty($arrArgs)){
				$bSkipCondLink=true;// 如果查询条件为空，忽略该项
				continue;
			}
			if(!is_null($bBoolItem)){
				$bBool=$bBoolItem;// 如果该项查询条件没有指定 AND/OR 关系，则不改变当前的 AND/OR 关系状态
			}
			if(!is_array($arrArgs)){
				if($arrArgs==self::BEGIN_GROUP){// 查询如果不是一个数组，则判断是否是特殊占位符
					if(!$bSkipCondLink){
						$sSql.=($bBool)?' AND ':' OR ';
					}
					$sSql.=self::BEGIN_GROUP;
					$bSkipCondLink=true;
				}else{
					$sSql.=self::END_GROUP;
				}
				continue;
			}else{
				if($bSkipCondLink){
					$bSkipCondLink=false;
				}else{
					$sSql.=($bBool)?' AND ':' OR ';// 如果 $bSkipCondLink 为 false，表示前一个项目是一个查询条件&因此需要用 AND/OR 来连接多个查询条件。
				}
			}
			$cond=reset($arrArgs);// 剥离出查询条件，$arrArgs 剩下的内容是查询参数
			array_shift($arrArgs);
			// 如果是这样的数组 array(0=>array('hello','world','ye'))，那么取第一个元素为数组
			if(isset($arrArgs[0]) && is_array($arrArgs[0]) && !isset($arrArgs[1])){
				$arrArgs=array_shift($arrArgs);
			}
			if($cond instanceof DbCond || $cond instanceof DbExpression){
				$sOption=$cond->makeSql($oConnect,$sTableName,$arrFieldsMapping,$hCallback);// 使用 DbCond 作为查询条件
			}elseif(is_array($cond)){
				$arrOptions=array();// 使用数组作为查询条件
				foreach($cond as $field=>$value){
					if(!is_string($field)){
						if(is_null($value)){// 如果键名不是字符串，说明键值是一个查询条件
							continue;
						}
						if($value instanceof DbCond || $cond instanceof DbExpression){
							$value=$value->makeSql($oConnect,$sTableName,$arrFieldsMapping,$hCallback);// 查询条件如果是 DbCond 或 DbExpr，则格式化为字符串
						}
						$value=$oConnect->qualifySql($value,$sTableName,$arrFieldsMapping,$hCallback);
						$style=(strpos($value,'?')===false)?Db::PARAM_CL_NAMED :Db::PARAM_QM;
						$arrOptions[]=$oConnect->qualifyInto($value,$arrArgs,$style);
					}else{
						$arrOptions[]=$oConnect->qualifyWhere(array($field=>$value),$sTableName,$arrFieldsMapping,$hCallback);// 转义查询值
					}
				}
				foreach($arrOptions as $sK=>$sV){
					if($sV=='OR'){
						$bBool=false;
						unset($arrOptions[$sK]);
					}
					if($sV=='AND'){
						unset($arrOptions[$sK]);
					}
				}
				$sAndOr=$bBool?' AND ':' OR ';// 用 AND or OR 连接多个查询条件
				$sOption=implode(' '.$sAndOr.' ',$arrOptions);
			}else{
				$sOption=$oConnect->qualifySql($cond,$sTableName,$arrFieldsMapping,$hCallback);// 使用字符串做查询条件
				$style=(strpos($sOption,'?')===false)?Db::PARAM_CL_NAMED :Db::PARAM_QM;
				$sOption=$oConnect->qualifyInto($sOption,$arrArgs,$style);
			}
			if((empty($sOption) || $sOption=='()')){
				$bSkipCondLink=true;
				continue;
			}
			$arrBigSql[]=$sOption;
			unset($sOption);
		}
		$arrBigSql=array_unique($arrBigSql);// 过滤空值和重复值
		$arrBigSql=Dyhb::normalize($arrBigSql);
		if(empty($arrBigSql)){
			return '';
		}
		return implode(' ',$arrBigSql);
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   MySQL 数据库记录集($)*/
class DbRecordSetMysql extends DbRecordSet{
	public function free(){
		// 获取查询结果指针
		$hResult=$this->getQueryResultHandle();
		if($hResult){
			mysql_free_result($hResult);
		}
		$this->setQueryResultHandle(null);
	}
	public function fetch(){
		$hResult=$this->getQueryResultHandle();
		if($this->_nFetchMode==Db::FETCH_MODE_ASSOC){// 以关联数组的方式返回数据库结果记录
			$arrRow=mysql_fetch_assoc($hResult);
			if($this->_bResultFieldNameLower && $arrRow){
				$arrRow=array_change_key_case($arrRow,CASE_LOWER);
			}
		}else{// 以索引数组的方式返回结果记录
			$arrRow=mysql_fetch_array($hResult);
		}
		return $arrRow;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   数据库 记录集($)*/
abstract class DbRecordSet{
	public $_nFetchMode;
	public $_bResultFieldNameLower=false;
	private $_arrData=array();
	protected $_nCount=0;
	protected $_oConnect;
	protected $_runSelectSql='';
	private $_hResult=null;
	public function __construct(DbConnect $oConnect,$nFetchMode=Db::FETCH_MODE_ARRAY){
		$this->_oConnect=$oConnect;
		$this->_nFetchMode=$nFetchMode;
	}
	public function __destruct(){
		$this->free();
	}
	public function setConnect(DbConnect $oConnect){
		$this->_oConnect=$oConnect;
		return $this;
	}
	public function getConnect(){
		return $this->_oConnect;
	}
	public function valid(){
		return $this->_hHandle!=null;
	}
	abstract public function free();
	public function reset($sOption=null){
		if($sOption==null){
			$this->_arrOptions=self::$_arrOptionsInit;
			$this->_arrQueryParams=self::$_arrQueryParamsInit;
		}elseif(array_key_exists($sOption,self::$_arrOptionsInit)){
			$this->_arrOptions[$sOption]=self::$_arrOptionsInit[$sOption];
		}
		return $this;
	}
	public function query($Sql){
		$this->_runSelectSql=$Sql;
		$oConnect=$this->getConnect();// 执行查询
		$Res=$oConnect->query($Sql);
		$this->_nCount=-1;// 重置
		$this->_arrData=array();
		if(!$Res){
			return false;
		}
		$this->setQueryResultHandle($Res);
		return true;
	}
	public function setQueryResultHandle($hRes){
		$hOldValue=$this->_hResult;
		$this->_hResult=$hRes;
		return $hOldValue;
	}
	public function getQueryResultHandle(){
		return $this->_hResult;
	}
	abstract public function fetch();
	public function getColumn($sColumn,$sSepa='-'){
		if(strpos($sColumn,',')){// 多个字段
			$arrRes=$this->getAllRows();
			if(!empty($arrRes)){
				$sColumn=explode(',',$sColumn);
				$sKey=array_shift($sColumn);
				$arrCols=array();
				foreach($arrRes as $sResult){
					$sName=$sResult[$sKey];
					$arrCols[$sName]='';
					foreach($sColumn as $sVal){$arrCols[$sName].=$sResult[$sVal].$sSepa;}
					$arrCols[$sName]=substr($arrCols[$sName],0,-strlen($sSepa));
				}
				return $arrCols;
			}
		}else{
			$arrResult=$this->fetch();
			if(!empty($arrResult)){
				return reset($arrResult);
			}
		}
		return null;
	}
	public function getRow($nRow=null){
		$arrRow=$this->fetch();
		if($nRow===null){
			return $arrRow;
		}
		if(isset($arrRow[$nRow])){
			return $arrRow[$nRow];
		}else{
			return null;
		}
	}
	public function getAllRows(){
		$arrRet=array();
		while(($arrRow=$this->fetch())!==false){
			$arrRet[]=$arrRow;
		}
		return $arrRet;
	}
	public function fetchCol($nCol=0){
		$nOldValue=$this->_nFetchMode;
		$this->_nFetchMode=Db::FETCH_MODE_ARRAY;
		$arrCols=array();
		while(($arrRow=$this->fetch())!==false){
			$arrCols[]=$arrRow[$nCol];
		}
		$this->_nFetchMode=$nOldValue;
		return $arrCols;
	}
	public function fetchAllRefby(array $arrFields,&$arrFieldsValue,&$arrRef,$bCleanUp){
		// 初始化查询参数
		$arrRef=$arrFieldsValue=$arrData=array();
		$nOffset=0;
		if ($bCleanUp){// 获取结果后释放内存
			while(($arrRow=$this->fetch())!==false){
				$arrData[$nOffset]=$arrRow;
				foreach($arrFields as $sField){
					$sFieldValue=$arrRow[$sField];
					$arrFieldsValue[$sField][$nOffset]=$sFieldValue;
					$arrRef[$sField][$sFieldValue][]=&$arrData[$nOffset];
					unset($arrData[$nOffset][$sField]);
				}
				$nOffset++;
			}
		}else{
			while(($arrRow=$this->fetch())!==false){
				$arrData[$nOffset]=$arrRow;
				foreach($arrFields as $sField){
					$sFieldValue=$arrRow[$sField];
					$fields_value[$sField][$nOffset]=$sFieldValue;
					$arrRef[$sField][$sFieldValue][]=&$arrData[$nOffset];
				}
				$nOffset++;
			}
		}
		return $arrData;
	}
	public function fetchObject($sClassName,$bReturnFirst=false){
		$arrObjs=array();
		$bIsAr=is_subclass_of($sClassName,'Model');
		while(($arrRow=$this->fetch())!==false){
			$oObj=$bIsAr?new $sClassName($arrRow,Db::FIELD,true):new $sClassName($arrRow);
			if($bReturnFirst)return $oObj;
			$arrObjs[]=$oObj;
		}
		return Coll::createFromArray($arrObjs,$sClassName);
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   Coll实现了一个类型安全的对象集合($)*/
class Coll implements Iterator,ArrayAccess,Countable{
	protected $_sType;
	protected $_arrColl=array();
	protected $_bIsValid=false;
	public function __construct($sType){
		$this->_sType=$sType;
	}
	public static function createFromArray(array $arrObjects,$sType,$bKeepKeys=false){
		$oColl=new self($sType);
		if($bKeepKeys){
			foreach($arrObjects as $offset=>$oObject){$arrColl[$offset]=$oObject;}
		}else{
			foreach($arrObjects as $oObject){$arrColl[]=$oObject;}
		}
		return $arrColl;
	}
	public function values($sPropName){
		$arrReturn=array();
		foreach(array_keys($this->_arrColl)as $offset){
			if(isset($this->_arrColl[$offset]->{$sPropName})){
				$arrReturn[]=$this->_arrColl[$offset]->{$sPropName};
			}
		}
		return $arrReturn;
	}
	public function offsetExists($Offset){
		return isset($this->_arrColl[$Offset]);
	}
	public function offsetGet($Offset){
		if(isset($this->_arrColl[$Offset])){
			return $this->_arrColl[$Offset];
		}
		Dyhb::E(sprintf('Invalid key name %s.',$Offset));
	}
	public function offsetSet($Offset,$Value){
		if(is_null($Offset)){
			$Offset=count($this->_arrColl);
		}
		$this->checkType_($Value);
		while(isset($this->_arrColl[$Offset])){
			$Offset++;
		}
		$this->_arrColl[$Offset]=$Value;
	}
	public function offsetUnset($Offset){
		unset($this->_arrColl[$Offset]);
	}
	public function current(){
		return current($this->_arrColl);
	}
	public function key(){
		return key($this->_arrColl);
	}
	public function next(){
		$this->_bIsValid=(false!==next($this->_arrColl));
	}
	public function rewind(){
		$this->_bIsValid=(false!==reset($this->_arrColl));
	}
	public function valid(){
		return $this->_bIsValid;
	}
	public function count(){
		return count($this->_arrColl);
	}
	public function isEmpty(){
		return empty($this->_arrColl);
	}
	public function first(){
		if(count($this->_arrColl)){
			return reset($this->_arrColl);
		}
		Dyhb::E(Dyhb::L('%s 集合中没有任何对象。','__DYHB__@Dyhb',null,$this->_sType));
	}
	public function last(){
		if(count($this->_arrColl)){
			$arrKeys=array_keys($this->_arrColl);
			$key=array_pop($arrKeys);
			return $this->_arrColl[$key];
		}
		Dyhb::E(Dyhb::L('%s 集合中没有任何对象。','__DYHB__@Dyhb',null,$this->_sType));
	}
	public function append($Data){
		foreach($Data as $oItem){
			$this->offsetSet(null,$oItem);
		}
		return $this;
	}
	public function search($sPropName,$Needle,$bStrict=false){
		foreach($this->_arrColl as $oItem){
			if($bStrict){
				if($oItem->{$sPropName}===$Needle){
					return $oItem;
				}
			}else{
				if($oItem->{$sPropName}==$Needle){
					return $oItem;
				}
			}
		}
		return null;
	}
	public function toHashMap($sKeyName,$sValueName=null){
		$arrRet=array();
		if($sValueName){
			foreach($this->_arrColl as $oObj){
				$arrRet[$oObj[$sKeyName]]=$oObj[$sValueName];
			}
		}else{
			foreach($this->_arrColl as $oObj){
				$arrRet[$oObj[$sKeyName]]=$oObj;
			}
		}
		return $arrRet;
	}
	public function __call($sMethod,$arrArgs){
		$bNotImplement=false;
		$sMethod=strtolower($sMethod);
		if(method_exists($this->_sType,'collCallback_')){
			$arrMap=call_user_func(array($this->_sType,'collCallback_'));
			$arrMap=array_change_key_case($arrMap,CASE_LOWER);
			if(isset($arrMap[$sMethod])){
				array_unshift($arrArgs,$this->_arrColl);
				return call_user_func_array(array($this->_sType,$arrMap[$sMethod]),$arrArgs);
			}
		}
		$arrResult=array();
		foreach($this->_arrColl as $oObj){
			$arrResult[]=call_user_func_array(array($oObj,$sMethod),$arrArgs);
		}
		return $arrResult;
	}
	protected function checkType_($oObject){
		if(is_object($oObject)){
			if($oObject instanceof $this->_sType){
				return;
			}
			$sType=get_class($oObject);
		}else{
			$sType=gettype($oObject);
		}
		Dyhb::E(Dyhb::L('集合只能容纳 %s 类型的对象，而不是 %s 类型的值.','__DYHB__@Dyhb',null ,$this->_sType,$sType));
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   ModelRelation封装Model之间的关联关系($)*/
class ModelRelation{
	public $_sSourceKey;
	public $_sTargetKey;
	public $_onFind='all';
	public $_onFindWhere=null;
	public $_sOnFindOrder=null;
	public $_onFindKeys='*';
	public $_onDelete='skip';
	public $_onDeleteSetValue=null;
	public $_sOnSave='skip';
	public $_sMidSourceKey;
	public $_sMidTargetKey;
	public $_midOnFindKeys=null;
	public $_sMidMappingTo;
	public $_bEnabled=true;
	public $_sType;
	protected $_bInited=false;
	public $_sMappingName;
	public $_sSourceKeyAlias;
	public $_sTargetKeyAlias;
	public $_oTargetMeta;
	public $_oSourceMeta;
	public $_oMidMeta;
	public $_oMidTable;
	public $_sTargetClass='';
	public $_bOneToOne=false;
	protected $_arrInitConfig;
	protected static $_arrInitConfigKeys=array(
		'_sMappingName',
		'_sSourceKey',
		'_sTargetKey',
		'_onFind',
		'_onFindWhere',
		'_sOnFindOrder',
		'_onFindKeys',
		'_onDelete',
		'_onDeleteSetValue',
		'_sOnSave',
		'_sMidSourceKey',
		'_sMidTargetKey',
		'_midOnFindKeys',
		'_sMidMappingTo',
		'_bEnabled',
		'_sTargetClass',
	);
	protected function __construct($sType,array $arrConfig,ModelMeta $oSourceMeta){
		$this->_sType=$sType;
		foreach(self::$_arrInitConfigKeys as $sKey){
			if(!empty($arrConfig[$sKey])){
				$this->{$sKey}=$arrConfig[$sKey];
			}
		}
		$this->_arrInitConfig=$arrConfig;
		$this->_oSourceMeta=$oSourceMeta;
	}
	static function create($sType,array $arrConfig,ModelMeta $oSourceMeta){
		if(empty($arrConfig['_sMappingName'])){// 缺少关联对象mapping_name属性，则抛出异常
			Dyhb::E(Dyhb::L('创建关联必须指定关联的 mapping_name 属性。','__DYHB__@Dyhb'));
		}else{
			$arrConfig['_sMappingName']=$arrConfig['_sMappingName'];
		}
		if(!in_array($sType,array(Db::HAS_ONE,Db::HAS_MANY,Db::BELONGS_TO,Db::MANY_TO_MANY))){// $sType参数检测
			Dyhb::E(Dyhb::L('无效的关联类型 %s。','__DYHB__@Dyhb',null,$sType));
		}
		switch($sType){// 根据不同的关联类型创建不同的关联类型
			case Db::HAS_ONE:
				return new ModelRelationHasOne(Db::HAS_ONE,$arrConfig,$oSourceMeta);
			case Db::HAS_MANY:
				return new ModelRelationHasMany(Db::HAS_MANY,$arrConfig,$oSourceMeta);
			case Db::BELONGS_TO:
				return new ModelRelationBelongsTo(Db::BELONGS_TO,$arrConfig,$oSourceMeta);
			case Db::MANY_TO_MANY:
				return new ModelRelationManyToMany(Db::MANY_TO_MANY, $arrConfig, $oSourceMeta);
			default:
				Dyhb::E(Dyhb::L('无效的关联类型 %s。','__DYHB__@Dyhb',null ,$sType));
		}
	}
	public function init_(){
		if(!$this->_bInited){
			$this->_oTargetMeta=ModelMeta::instance($this->_arrInitConfig['_sTargetClass']);
			$this->_sSourceKeyAlias=$this->_sMappingName.'_source_key';
			$this->_sTargetKeyAlias=$this->_sMappingName.'_target_key';
			$this->_bInited=true;
		}
		return $this;
	}
	public function regCallback(array $arrAssocInfo){
		return $this;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   ModelAssociationBelongsTo类封装了对象见的隶属关系($)*/
class ModelRelationBelongsTo extends ModelRelation{
	public $_bOneToOne=true;
	public $_onDelete='skip';
	public $_sOnSave='skip';
	public function init_(){
		if($this->_bInited){
			return $this; 
		}
		parent::init_();
		
		$arrP=$this->_arrInitConfig;
		$this->_sSourceKey=!empty($arrP['_sSourceKey'])?$arrP['_sSourceKey']:reset($this->_oTargetMeta->_arrIdName);
		$this->_sTargetKey=!empty($arrP['_sTargetKey'])?$arrP['_sTargetKey']:reset($this->_oTargetMeta->_arrIdName);
		unset($this->_arrInitConfig);
		return $this;
	}
	public function onSourceSave(Model $oSource,$nRecursion){
		return $this;
	}
	public function onSourceDestroy(Model $oSource){
		return $this;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   ModelRelationHasMany类封装了对象见的一对多关系($)*/
class ModelRelationHasMany extends ModelRelation{
	public $_bOneToOne=false;
	public $_onDelete='cascade';
	public $_sOnSave='save';
	public function init_(){
		if($this->_bInited){
			return $this;
		}
		parent::init_();
		$arrP=$this->_arrInitConfig;
		$this->_sSourceKey=!empty($arrP['_sSourceKey'])?$arrP['_sSourceKey']:reset($this->_oSourceMeta->_arrIdName);
		$this->_sTargetKey=!empty($arrP['_sTargetKey'])?$arrP['_sTargetKey']:reset($this->_oSourceMeta->_arrIdName);
		unset($this->_arrInitConfig);
		return $this;
	}
	public function regCallback(array $arrAssocInfo){
		return $this;
	}
	public function addTarget(Model $oSource,Model $oTarget){
		$this->init_();
		$sMappingName=$this->_sMappingName;
		if(!isset($oSource->{$sMappingName})){
			$oSource->{$sMappingName}=array($oTarget);
		}else{
			$oSource[$sMappingName][]=$oTarget;
		}
	}
	public function onSourceSave(Model $oSource,$nRecursion){
		$this->init_();
		$sMappingName=$this->_sMappingName;
		if($this->_sOnSave=='skip' || $this->_sOnSave===false || !isset($oSource->{$sMappingName})){
			return $this;
		}
		$sSourceKeyValue=$oSource->{$this->_sSourceKey};
		foreach($oSource->{$sMappingName} as $oObj){
			$oObj->changePropForce($this->_sTargetKey,$sSourceKeyValue);
			$oObj->save($nRecursion-1,$this->_sOnSave);
		}
		return $this;
	}
	public function onSourceDestroy(Model $oSource){
		$this->init_();
		if($this->_onDelete===false || $this->_onDelete=='skip'){
			return $this;
		}
		$sSourceKeyValue=$oSource->{$this->_sSourceKey};
		$arrCond=array($this->_sTargetKey=>$sSourceKeyValue);
		if($this->_onDelete===true || $this->_onDelete=='cascade'){
			$this->_oTargetMeta->destroyWhere($arrCond);
		}elseif($this->_onDelete=='reject'){
			$arrRow=$this->_oTargetMeta->find($arrCond)->count()->query();
			if(intval($arrRow['row_count'])> 0){
				Dyhb::E(Dyhb::L('对象 %s 的关联 %s 拒绝了对象的删除操作.','__DYHB__@Dyhb',null,$this->_oSourceMeta->_sClassName,$this->_sMappingName));
			}
		}else{
			$sFill=($this->_onDelete=='set_null')?null:$this->_sOnDeleteSetValue;
			$this->_oTargetMeta->updateWhere($arrCond,array($this->_sTargetKey=>$sFill));
		}
		return $this;
	}
	public function addRelatedObject(Model $oSource,Model $oTarget){
		$this->init_();
		$oTarget->changePropForce($this->_sTargetKey,$oSource->{$this->_sSourceKey});
		$oTarget->save(0,$this->_sOnSave);
		return $this;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   ModelRelationHasOne类封装了对象见的一对一关系($)*/
class ModelRelationHasOne extends ModelRelationHasMany{
	public $_bOneToOne=true;
	public $_sOnSave='replace';
	public function onSourceSave(Model $oSource,$nRecursion){
		$this->init_();
		$sMappingName=$this->_sMappingName;
		if($this->_sOnSave==='skip' || $this->_sOnSave===false || !isset($oSource->{$sMappingName})){
			return $this;
		}
		$sSourceKeyValue=$oSource->{$this->_sSourceKey};
		$oObj=$oSource->{$sMappingName};
		$oObj->changePropForce($this->_sTargetKey,$sSourceKeyValue);
		$oObj->save($nRecursion-1,$this->_sOnSave);
		return $this;
	}
	public function addRelatedObject(Model $oSource,Model $oTarget){
		return $this;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   ModelRelationManyToMany类封装了对象见的多对多关系($)*/
class ModelRelationManyToMany extends ModelRelation{
	public $_bOneToOne=false;
	public $_onDelete='skip';
	public $_sOnSave='skip';
	public function init_(){
		if($this->_bInited){
			return $this;
		}
		parent::init_();
		$arrP=$this->_arrInitConfig;
		if(empty($arrP['_sMidClass'])){
			$this->_oMidMeta=null;// 如果没有指定中间表对应的 Model 类，则使用表数据入口直接处理中间表
			if(empty($arrP['_sMidTableName'])){
				$sT1=$this->_oSourceMeta->_oTable->_sName;// 尝试自动确定中间表名称
				$sT2=$this->_oTargetMeta->_oTable->_sName;
				if($sT1<=$sT2){
					$sMidTableName=$sT1.'_has_'.$sT2;
				}else{
					$sMidTableName=$sT2.'_has_'.$sT1;
				}
			}else{
				$sMidTableName=$arrP['_sMidTableName'];
			}
			$this->_oMidTable=new DbTableEnter(array('name'=>$sMidTableName));
		}else{
			$this->_oMidMeta=ModelMeta::instance($arrP['_sMidClass']);// 如果中间表作为实体，则由指定的 Model 继承类负责处理中间表
			$this->_oMidTable=$this->_oMidMeta->_oTable;
		}
		$this->_sSourceKey=!empty($arrP['_sSourceKey'])?$arrP['_sSourceKey']:reset($this->_oSourceMeta->_arrIdName);
		$this->_sTargetKey=!empty($arrP['_sTargetKey'])?$arrP['_sTargetKey']:reset($this->_oTargetMeta->_arrIdName);
		$this->_sMidSourceKey=!empty($arrP['_sMidSourceKey'])?$arrP['_sMidSourceKey']:reset($this->_oSourceMeta->_arrIdName);
		$this->_sMidTargetKey=!empty($arrP['_sMidTargetKey'])?$arrP['_sMidTargetKey']:reset($this->_oTargetMeta->_arrIdName);
		$this->_sMidMappingTo=!empty($arrP['_sMidMappingTo'])?$arrP['_sMidMappingTo']:'mid_data';
		$sClass=$this->_oTargetMeta->_sClassName;
		unset($this->_arrInitConfig);
		return $this;
	}
	public function onSourceSave(Model $oSource,$nRecursion){
		$this->init_();
		
		$sMappingName=$this->_sMappingName;
		if($this->_sOnSave=='skip' || $this->_sOnSave===false || !isset($oSource->{$sMappingName})){
			return $this;
		}
		if(($nRecursion-1)<0){
			return $this;
		}
		return $this->updateRelation_($oSource,$nRecursion);
	}
	public function onSourceDestroy(Model $oSource){
		$this->init_();
		if($this->_sOnSave=='skip' || $this->_sOnSave===false){
			return $this;
		}
		$oSource->{$this->_sMappingName}=array();
		return $this->updateRelation_($oSource);
	}
	public function bindRelatedObject(Model $oSource,Model $oTarget){
		$this->init_();
		if(!$this->_oMidMeta){
			$oConnect=$this->_oMidTable->getConnect();
			$oTarget->save($this->_sOnSave);
			$sSourceKeyValue=$oSource->{$this->_sSourceKey};
			$sTargetKeyValue=$oTarget->{$this->_sTargetKey};
			$sSql=sprintf('SELECT COUNT(*)FROM %s WHERE %s=%s AND %s=%s',
					$oConnect->qualifyId($this->_oMidTable->getFullTableName()),
					$oConnect->qualifyId($this->_sMidSourceKey),
					$oConnect->qualifyStr($sSourceKeyValue),
					$oConnect->qualifyId($this->_sMidTargetKey),
					$oConnect->qualifyStr($sTargetKeyValue)
			);
			if(intval($oConnect->getOne($sSql))<1){
				$this->_oMidTable->insert(
					array($this->_sMidSourceKey=>$sSourceKeyValue,$this->_sMidTargetKey=>$sTargetKeyValue)
				);
			}
		}
		return $this;
	}
	public function unbindRelatedObject(Model $oSource,Model $oTarget){}
	protected function updateRelation_(Model $oSource,$nRecursion=1){
		$this->init_();
		if(!isset($oSource->{$this->_sMappingName})){
			return $this;
		}
		if($this->_oMidMeta){
			return $this->updateRelationByMeta_($oSource,$oSource->{$this->_sMappingName},$nRecursion);
		}else{
			return $this->updateRelationByTable_($oSource,$oSource->{$this->_sMappingName},$nRecursion);
		}
	}
	protected function updateRelationByMeta_(Model $oSource,$arrTargets,$nRecursion){
		foreach($arrTargets as $oObj){
			$oObj->save($nRecursion-1,$this->_sOnSave);
			$sV=$oObj->{$this->_sTargetKey};
			$arrTargets[$sV]=$oObj;
		}
		$sSourceKeyValue=$oSource->{$this->_sSourceKey};// 取出现有的关联信息
		$oExists=$this->_oMidMeta->find(array($this->_sMidSourceKey=>$sSourceKeyValue))
			->all()
			->asColl()
			->query();
		$arrAdded=array();// 然后确定要添加和删除的关联信息
		foreach($arrTargets as $oTarget){
			$sV=$oTarget->{$this->_sTargetKey};
			if(!$oExists->search($this->_sTargetKey,$sV)){
				$arrAdded[]=$sV;
			}
		}
		$arrRemoved=array();
		foreach($oExists as $oExist){
			$sV=$oExist->{$this->_sTargetKey};
			if(!isset($arrTargets[$sV])){
				$arrRemoved[]=$sV;
			}
		}
		foreach($arrAdded as $sMidTargetKeyValue){// 添加新增的关联信息
			$oMid=$this->_oMidMeta->newObj();
			$oMid->changePropForce($this->_sMidSourceKey,$sSourceKeyValue);
			$oMid->changePropForce($this->_sMidTargetKey,$sMidTargetKeyValue);
			$oMid->save();
		}
		if(!empty($arrRemoved)){// 删除多余的关联信息
			$this->_oMidMeta->destroyWhere("{$this->_sMidSourceKey}=? AND {$this->_sMidTargetKey} IN(?)",$sSourceKeyValue,$arrRemoved);
		}
		return $this;
	}
	protected function updateRelationByTable_(Model $oSource,$arrTargets,$nRecursion){
		$oConnect=$this->_oMidTable->getConnect();// 取出现有的关联信息
		$arrTargetKeyValues=array();
		foreach($arrTargets as $oObj){
			$oObj->save($this->_sOnSave,$nRecursion-1);
			$arrTargetKeyValues[]=$oObj->{$this->_sTargetKey};
		}
		$sSourceKeyValue=$oSource->{$this->_sSourceKey};
		$sSql=sprintf('SELECT %s FROM %s WHERE %s=%s',
			$this->qualifyId($oConnect,$this->_sMidTargetKey),
			$this->qualifyId($oConnect,$this->_oMidTable->getFullTableName()),
			$this->qualifyId($oConnect,$this->_sMidSourceKey),
			$this->qualifyStr($oConnect,$sSourceKeyValue)
		);
		$arrExistsMid=$oConnect->getCol($sSql);
		$arrInsertMid=array_diff($arrTargetKeyValues,$arrExistsMid);// 然后确定要添加和删除的关联信息
		$arrRemoveMid=array_diff($arrExistsMid,$arrTargetKeyValues);
		foreach($arrInsertMid as $sMidTargetKeyValue){// 添加新增的关联信息
			$this->_oMidTable->insert(array($this->_sMidSourceKey=>$sSourceKeyValue,$this->_sMidTargetKey=>$sMidTargetKeyValue));
		}
		
		if(!empty($arrRemoveMid)){// 删除多余的关联信息
			$this->_oMidTable->delete("{$this->_sMidSourceKey}=? AND {$this->_sMidTargetKey} IN(?)",$sSourceKeyValue,$arrRemoveMid);
		}
		return $this;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   ModelRelationColl 封装了对象的关联关系，并且提供操作这些关联关系的方法($)*/
class ModelRelationColl extends Coll{}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   Page分页处理类($)*/
class Page{
	protected $_nCount;
	protected $_nSize;
	protected $_nPage;
	protected $_nPageStart;
	protected $_sParameter;
	protected $_nPageCount;
	protected $_nPageI;
	protected $_nPageUb;
	protected $_nPageLimit;
	protected $_bPageSkip=false;
	protected static $_oDefaultDbIns=null;
	protected $_sPagename='page';
	protected function __construct($nCount=0,$nSize=1,$nPage=1){
		// 总记录数量
		$this->_nCount=$this->numeric($nCount);
		// 每页数量
		$this->_nSize=$this->numeric($nSize);
		// 当前page页码
		$this->_nPage=$this->numeric($nPage); 
		$this->_nPageLimit=($this->_nPage*$this->_nSize)-$this->_nSize;
		// 页码如果小于1，则初始化为1，页码当然最小为1
		if($this->_nPage<1){
			$this->_nPage=1;
		}
		// 如果记录为<0,那么这个时候也不存在页码了
		if($this->_nCount<0){
			$this->_nPage=0;
		}
		// 取得所有页面数，ceil进一取整
		$this->_nPageCount=ceil($this->_nCount/$this->_nSize); 
		if($this->_nPageCount<1){
			$this->_nPageCount=1;
		}
		// 如果页面大于总记录数，那么这个记录直接取总记录数的大小
		if($this->_nPage>$this->_nPageCount){
			$this->_nPage=$this->_nPageCount; 
		}
		$this->_nPageI=$this->_nPage-2;
		$this->_nPageUb=$this->_nPage+2;
		if($this->_nPageI<1){
			$this->_nPageUb=$this->_nPageUb+(1-$this->_nPageI);
			$this->_nPageI=1;
		}
		if($this->_nPageUb>$this->_nPageCount){
			$this->_nPageI=$this->_nPageI-($this->_nPageUb-$this->_nPageCount);
			$this->_nPageUb=$this->_nPageCount;
			if($this->_nPageI<1){
				$this->_nPageI=1;
			}
		}
		$this->_nPageStart=($nPage-1)*$this->_nSize;
		if($this->_nPageStart<0){
			$this->_nPageStart=0;
		}
	}
	public static function RUN($nCount=0,$nSize=1,$nPage=1,$bDefaultIns=true){
		if($bDefaultIns and self::$_oDefaultDbIns){
			return self::$_oDefaultDbIns;
		}
		$oPage=new self($nCount,$nSize,$nPage);// 创建一个分页对象
		if($bDefaultIns){// 设置全局对象
			self::$_oDefaultDbIns=$oPage;
		}
		return $oPage;
	}
	public function P($Id='pagenav',$sTyle='span',$sCurrent='current',$sDisabled='disabled',$sPagename='page'){
		$sStr='';
		if(!empty($sPagename)){
			$this->_sPagename=$sPagename;
		}
		if(!empty($Id)){
			// 增加分页条自定义CSS样式
			$arrIddata=explode('@',$Id);
			$Id=$arrIddata[0];
			$sClass=isset($arrIddata[1])?$arrIddata[1]:$Id;
			$sStr='<div id="'.$Id.'" class="'.$sClass.'">';
		}
		if($sTyle=='li'){
			$sStr.='<ul>';
		}
		$sStr.="<{$sTyle} class=\"{$sDisabled}\">".($sTyle=='li'?'<a href="javascript:void(0);">':'');// 总记录数量
		if($this->_nCount>0){// 显示记录总数
			$sStr.='Total:'.$this->_nCount;
		}else{
			$sStr.='No Record';
		}
		$sStr.=($sTyle=='li'?'</a>':'')."</{$sTyle}>";
		if($this->_nPageCount>1){// 页码
			$sStr.=$this->home($sTyle);
			$sStr.=$this->prev($sTyle);
			for($nI=$this->_nPageI;$nI<=$this->_nPageUb;$nI++){
				if($this->_nPage==$nI){
					$sStr.="<{$sTyle} class=\"{$sCurrent}\">".($sTyle=='li'?'<a href="javascript:void(0);">':'').$nI.($sTyle=='li'?'</a>':'')."</{$sTyle}>";
				}else{
					$sStr.=($sTyle=='li'?'<li>':'').'<a href="'.$this->pageReplace($nI).'" title="'.sprintf('Page %d',$nI).'">';
					$sStr.=$nI."</a>".($sTyle=='li'?'</li>':'');
				}
			}
			$sStr.=$this->next($sTyle);
			$sStr.=$this->last($sTyle);
			if($this->_bPageSkip){
				$sStr.='<input type="text" id="page_skip_id" size="2" value="'.$this->_nPage.'"';
				$sStr.='title="Enter the page number you want to reach" />';
				$sStr.=' <input type="button" class="button" size="4"';
				$sStr.='onclick="javascript:if(document.getElementById(\'page_skip_id\').value<='.$this->_nPageCount.'){location=\'';
				$sStr.=$this->pageReplace('\'+document.getElementById(\'page_skip_id\').value').';}else{ alert(\'Page number to jump over the total number of pages\'); }"';
				$sStr.=' value="Jump" />';
			}
		}
		if($sTyle=='li'){
			$sStr.='</ul>';
		}
		if(!empty($Id)){
			$sStr.='</div>';
		}
		return $sStr;
	}
	public function setPageSkip($bPageSkip=true){
		$bOldValue=$this->_bPageSkip;
		$this->_bPageSkip=$bPageSkip;
		return $bOldValue;
	}
	public function setParameter($sParameter=''){
		$sOldValue=$this->_sParameter;
		$this->_sParameter=$sParameter;
		return $sOldValue;
	}
	public function returnCount(){
		return $this->_nCount;
	}
	public function returnSize(){
		return $this->_nSize;
	}
	public function returnPage(){
		return $this->_nPage;
	}
	public function returnPageStart(){
		return $this->_nPageStart;
	}
	public function returnPageCount(){
		return $this->_nPageCount;
	}
	public function returnPageI(){
		return $this->_nPageI;
	}
	public function returnPageUb(){
		return $this->_nPageUb;
	}
	public function returnPageLimit(){
		return $this->_nPageLimit;
	}
	protected function numeric($Id){
		if(strlen($Id)){
			if(!preg_match("/^[0-9]+$/",$Id)){$Id=1;}
			else{$Id=substr($Id,0,11);}
		}else{
			$Id=1;
		}
		return $Id;
	}
	protected function pageReplace($nPage){
		$sPageUrl=$_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'&':'?').$this->_sParameter;
		$arrParse=parse_url($sPageUrl);
		if(isset($arrParse['query'])){// 分析URL
			$arrParams=array();
			parse_str($arrParse['query'],$arrParams);
			unset($arrParams[$this->_sPagename]);
			$sPageUrl= $arrParse['path'].'?'.http_build_query($arrParams);
		}
		$sPageUrl=str_replace(array("%2F","%3D","%3F"),array('/','=','?'),$sPageUrl);
		$sPageUrl.=(substr($sPageUrl,-1,1)!='?'?'&':'').$this->_sPagename.'='.$nPage;
		return $sPageUrl;
	}
	protected function home($sTyle='span'){
		// 页面不为第一页，加上超衔接
		if($this->_nPage!=1){
			return ($sTyle=='li'?'<li>':'').'<a href="'.$this->pageReplace(1).'" title="Home" >&laquo; First</a>'.($sTyle=='li'?'</li>':'');
		}
	}
	protected function prev($sTyle='span'){
		if($this->_nPage!=1){
			return ($sTyle=='li'?'<li>':'').'<a href="'.$this->pageReplace($this->_nPage-1).'" title="Previous" >&#8249; Prev</a>'.($sTyle=='li'?'</li>':'');
		}
	}
	protected function next($sTyle='span'){
		if($this->_nPage!=$this->_nPageCount){
			return ($sTyle=='li'?'<li>':'').'<a href="'.$this->pageReplace($this->_nPage+1).'" title="Next" >Next &#8250;</a>'.($sTyle=='li'?'</li>':'');
		}
	}
	protected function last($sTyle='span'){
		if($this->_nPage!=$this->_nPageCount){
			return ($sTyle=='li'?'<li>':'').'<a href="'.$this->pageReplace($this->_nPageCount).'" title="Last" >Last &raquo;</a>'.($sTyle=='li'?'</li>':'');
		}
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   Web URL分析器($)*/
/** 支持的URL模式 */
define('URL_COMMON',0);// 普通模式
define('URL_PATHINFO',1);// PATHINFO模式
define('URL_REWRITE',2);// REWRITE模式
define('URL_COMPAT',3);// 兼容模式
class Url{
	protected $_sLastRouterName=null;
	protected $_arrLastRouteInfo=array();
	static private $_sBaseUrl;
	static private $_sBaseDir;
	static private $_sRequestUrl;
	private $_oRouter=null;
	public $_sControllerName;
	public $_sActionName;
	public $_sAppName;
	public function parseUrl(){
		$_SERVER['REQUEST_URI']=isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:$_SERVER["HTTP_X_REWRITE_URL"];//For IIS
		
		$sDepr=$GLOBALS['_commonConfig_']['URL_PATHINFO_DEPR'];
		if($GLOBALS['_commonConfig_']['URL_MODEL']){
			$this->filterPathInfo();
			if($GLOBALS['_commonConfig_']['START_ROUTER']){
				$arrRouterInfo=$this->getRouterInfo();
				if(empty($arrRouterInfo)){
					$_GET=array_merge($this->parsePathInfo(),$_GET);
				}else{
					$_GET=array_merge($this->getRouterInfo(),$_GET);
				}
			}else{
				$_GET=array_merge($this->parsePathInfo(),$_GET);
			}
		}else{
			if($GLOBALS['_commonConfig_']['START_ROUTER'] && isset($_GET['r'])){
				$arrRouterInfo=$this->getRouterInfo();
				if(!empty($arrRouterInfo)){
					$_GET=array_merge($arrRouterInfo,$_GET);
				}else{
					$_GET=array_merge($this->getRouterInfo(),$_GET);
				}
			}else{
				$_GET=array_merge($this->parsePathInfo(),$_GET);
			}
		}
		if(!defined('APP_NAME')){
			define('APP_NAME',$_GET['app']=$this->getApp('app'));
		}
		define('MODULE_NAME',$_GET['c']=$this->getControl('c'));
		define('ACTION_NAME',$_GET['a']=$this->getAction('a'));
		
		// 当前页面地址
		define('__SELF__',$_SERVER['REQUEST_URI']);
		// 解析__APP__路径
		$this->parseAppPath();
		define('__APP__',PHP_FILE);
		define('__URL__',__APP__.'/'.MODULE_NAME);
		define('__ACTION__',__URL__.$sDepr.ACTION_NAME);
		$_REQUEST=array_merge($_POST,$_GET);
	}
	public function parseAppPath(){
		define('IS_CGI',substr(PHP_SAPI,0,3)=='cgi'?1:0);
		define('IS_CLI',PHP_SAPI=='cli'?1:0);
		if(!IS_CLI){
			if(!defined('_PHP_FILE_')){/** PHP 文件 */
				if(IS_CGI){
					$arrTemp=explode('.php',$_SERVER["PHP_SELF"]);// CGI/FASTCGI模式下
					define('_PHP_FILE_',rtrim(str_replace($_SERVER["HTTP_HOST"],'',$arrTemp[0].'.php'),'/'));
				}else{
					define('_PHP_FILE_',rtrim($_SERVER["SCRIPT_NAME"],'/'));
				}
			}
			/** 网站URL根目录 */
			if(strtoupper(APP_NAME)==strtoupper(basename(dirname(_PHP_FILE_)))){
				$sRoot=dirname(dirname(_PHP_FILE_));
			}else{
				$sRoot=dirname(_PHP_FILE_);
			}
			$sRoot=($sRoot=='/' || $sRoot=='\\')?'':$sRoot;
			define('__ROOT__',$GLOBALS['_commonConfig_']['URL_DOMAIN'].$sRoot);
			
			/** 网站相对根目录 */
			if(!isset($_SERVER['DOCUMENT_ROOT']) OR 
				(isset($_SERVER['PATH_TRANSLATED']) AND !preg_match('/'.str_replace('/','\/',str_replace('\\','/',dirname($_SERVER['DOCUMENT_ROOT']))).'/i',str_replace('\\','/', dirname($_SERVER['PATH_TRANSLATED']))))
			){
				if(strtoupper(APP_NAME)==strtoupper(basename(dirname(_PHP_FILE_)))){
					$nLength=strlen(_PHP_FILE_)-strlen(APP_NAME)-1;
				}else{
					$nLength=strlen(_PHP_FILE_)-1;
				}
				$_SERVER['DOCUMENT_ROOT']=substr(preg_replace('/\+/','/',$_SERVER['PATH_TRANSLATED']),0,$nLength);
			}
			$_SERVER['DOCUMENT_ROOT']=rtrim($_SERVER['DOCUMENT_ROOT'],'\\/');
			if($_SERVER['DOCUMENT_ROOT']===DYHB_PATH){
				define('WEB_ADMIN_HTTPPATH','/');
			}else{
				define('WEB_ADMIN_HTTPPATH',substr(DYHB_PATH,strlen($_SERVER['DOCUMENT_ROOT'])));
			}
			
			// 内部目录入口路径
			define('__FRAMEWORK__',__ROOT__.'/'.G::getRelativePath($sRoot.'/'.APP_NAME,WEB_ADMIN_HTTPPATH));
			
			// Resource目录路径
			define('__LIBCOM__',__FRAMEWORK__.'/Resource_');
		}
		$nUrlModel=$GLOBALS['_commonConfig_']['URL_MODEL'];
		if($GLOBALS['_commonConfig_']['URL_MODEL']===URL_REWRITE){// 如果为重写模式
			$sUrl=dirname(_PHP_FILE_);
			if($sUrl=='\\'){
				$sUrl='/';
			}
			define('PHP_FILE',$sUrl);
		}elseif($GLOBALS['_commonConfig_']['URL_MODEL']===URL_COMPAT){
			define('PHP_FILE',_PHP_FILE_.'?s=');
		}else{
			define('PHP_FILE',_PHP_FILE_);
		}
	}
	private function getRouterInfo(){
		if(is_null($this->_oRouter)){
			$this->_oRouter=new Router($this);
		}
		$this->_oRouter->import();// 导入路由规则
		$this->_arrLastRouteInfo=$this->_oRouter->G();// 获取路由信息
		$this->_sLastRouterName =$this->_oRouter->getLastRouterName();
		return $this->_arrLastRouteInfo;
	}
	public function getLastRouterName(){
		return $this->_sLastRouterName;
	}
	public function getLastRouterInfo(){
		return $this->_arrLastRouteInfo;
	}
	public function requestUrl(){
		if(self::$_sRequestUrl){
			return self::$_sRequestUrl;
		}
		if(isset($_SERVER['HTTP_X_REWRITE_URL'])){
			$sUrl=$_SERVER['HTTP_X_REWRITE_URL'];
		}elseif(isset($_SERVER['REQUEST_URI'])){
			$sUrl=$_SERVER['REQUEST_URI'];
		}elseif(isset($_SERVER['ORIG_PATH_INFO'])){
			$sUrl=$_SERVER['ORIG_PATH_INFO'];
			if(!empty($_SERVER['QUERY_STRING'])){
				$sUrl.='?'.$_SERVER['QUERY_STRING'];
			}
		}else{
			$sUrl='';
		}
		self::$_sRequestUrl=$sUrl;
		return $sUrl;
	}
	public function baseDir(){
		if(self::$_sBaseDir){
			return self::$_sBaseDir;
		}
		$sBaseUrl=$this->baseUrl();
		if(substr($sBaseUrl, - 1, 1)== '/'){
			$sBaseDir=$sBaseUrl;
		}else{
			$sBaseDir=dirname($sBaseUrl);
		}
		self::$_sBaseDir=rtrim($sBaseDir,'/\\').'/';
		return self::$_sBaseDir;
	}
	public function baseUrl(){
		if(self::$_sBaseUrl){
			return self::$_sBaseUrl;
		}
		$sFileName=basename($_SERVER['SCRIPT_FILENAME']);
		if(basename($_SERVER['SCRIPT_NAME'])===$sFileName){
			$sUrl=$_SERVER['SCRIPT_NAME'];
		}elseif(basename($_SERVER['PHP_SELF'])===$sFileName){
			$sUrl=$_SERVER['PHP_SELF'];
		}elseif(isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME'])===$sFileName){
			$sUrl=$_SERVER['ORIG_SCRIPT_NAME'];
		}else{
			$sPath=$_SERVER['PHP_SELF'];
			$arrSegs=explode('/',trim($_SERVER['SCRIPT_FILENAME'],'/'));
			$arrSegs=array_reverse($arrSegs);
			$nIndex=0;
			$nLast=count($arrSegs);
			$sUrl='';
			do{
				$sSeg=$arrSegs[$nIndex];
				$sUrl='/'.$sSeg.$sUrl;
				++ $nIndex;
			}while(($nLast>$nIndex) && (false!==($nPos=strpos($sPath,$sUrl))) && (0!=$nPos));
		}
		$sRequestUrl=$this->requestUrl();
		if(0===strpos($sRequestUrl,$sUrl)){
			self::$_sBaseUrl=$sUrl;
			return self::$_sBaseUrl;
		}
		if(0===strpos($sRequestUrl,dirname($sUrl))){
			self::$_sBaseUrl=rtrim(dirname($sUrl),'/').'/';
			return self::$_sBaseUrl;
		}
		if(!strpos($sRequestUrl,basename($sUrl))){
			return '';
		}
		if((strlen($sRequestUrl)>=strlen($sUrl)) && ((false!==($nPos=strpos($sRequestUrl,$sUrl))) && ($nPos!==0))){
			$sUrl=substr($sRequestUrl,0,$nPos+strlen($sUrl));
		}
		self::$_sBaseUrl=rtrim($sUrl,'/').'/';
		return self::$_sBaseUrl;
	}
	public function pathinfo(){
		if(!empty($_SERVER['PATH_INFO'])){
			return $_SERVER['PATH_INFO'];
		}
		$sBaseUrl=$this->baseUrl();
		if(null===($sRequestUrl=$this->requestUrl())){
			return '';
		}
		if(($nPos=strpos($sRequestUrl,'?'))>0){
			$sRequestUrl=substr($sRequestUrl,0,$nPos);
		}
		if((null!==$sBaseUrl) && (false===($sPathinfo=substr($sRequestUrl,strlen($sBaseUrl))))){
			$sPathinfo='';
		}elseif(null===$sBaseUrl){
			$sPathinfo=$sRequestUrl;
		}
		return $sPathinfo;
	}
	public function parsePathInfo(){
		$arrPathInfo=array();
		$sPathInfo=&$_SERVER['PATH_INFO'];
		if($GLOBALS['_commonConfig_']['URL_PATHINFO_MODEL']==2){
			$arrPaths=explode($GLOBALS['_commonConfig_']['URL_PATHINFO_DEPR'],trim($sPathInfo,'/'));
			if($arrPaths[0]=='app'){
				array_shift($arrPaths);
				$arrPathInfo['app']=array_shift($arrPaths);
			}
			if(!isset($_GET['c'])){// 还没有定义模块名称
				$arrPathInfo['c']=array_shift($arrPaths);
			}
			$arrPathInfo['a']=array_shift($arrPaths);
			for($nI=0,$nCnt=count($arrPaths);$nI<$nCnt;$nI++){
				if(isset($arrPaths[$nI+1])){
					$arrPathInfo[$arrPaths[$nI]]=(string)$arrPaths[++$nI];
				}elseif($nI==0){
					$arrPathInfo[$arrPathInfo['a']]=(string)$arrPaths[$nI];
				}
			}
		}else{
			$bRes=preg_replace('@(\w+)'.$GLOBALS['_commonConfig_']['URL_PATHINFO_DEPR'].'([^,\/]+)@e','$arrPathInfo[\'\\1\']="\\2";',$sPathInfo);
		}
		return $arrPathInfo;
	}
	protected function getControl($sVar){
		$sControl=(!empty($_GET[$sVar])?$_GET[$sVar]:$GLOBALS['_commonConfig_']['DEFAULT_CONTROL']);
		$this->_sControllerName=strtolower($sControl);
		return $this->_sControllerName;
	}
	protected function getAction($sVar){
		$sAction=!empty($_POST[$sVar])?$_POST[$sVar]:(!empty($_GET[$sVar])?$_GET[$sVar]:$GLOBALS['_commonConfig_']['DEFAULT_ACTION']);
		$this->_sActionName=strtolower($sAction);
		return $this->_sActionName;
	}
	protected function getApp($sVar){
		$sApp=!empty($_POST[$sVar])?$_POST[$sVar]:(!empty($_GET[$sVar])?$_GET[$sVar]:basename(APP_PATH));
		$this->_sAppName=strtolower($sApp);
		return $this->_sAppName;
	}
	public function control(){
		return $this->_sControllerName;
	}
	public function action(){
		return $this->_sActionName;
	}
	public function filterPathInfo(){
		if(!empty($_GET['s'])){
			$sPathInfo=$_GET['s'];
			unset($_GET['s']);
		}else{
			$sPathInfo=$this->pathinfo();
		}
		$sPathInfo=$this->clearHtmlSuffix($sPathInfo);
		$sPathInfo= empty($sPathInfo)?'/':$sPathInfo;
		$_SERVER['PATH_INFO']=$sPathInfo;
	}
	protected function clearHtmlSuffix($sVal){
		if($GLOBALS['_commonConfig_']['URL_HTML_SUFFIX'] && !empty($sVal)){
			$sSuffix=substr($GLOBALS['_commonConfig_']['URL_HTML_SUFFIX'],1);
			$sVal=preg_replace('/\.'.$sSuffix.'$/','',$sVal);
		}
		return $sVal;
	}
}
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   系统路由解析类($)*/
class Router{
	protected $_sLastRouterName=null;
	protected $_arrLastRouteInfo=array();
	protected $_arrRouters=array();
	protected $_oUrlParseObj=null;
	public function __construct($oUrlParseObj=null){
		if(is_null($oUrlParseObj)){
			$this->_oUrlParseObj=new Url();
		}else{
			$this->_oUrlParseObj=$oUrlParseObj;
		}
	}
	public function G($sRouterName=null){
		$sCurrentName=$sRouterName?$sRouterName:$this->getRouterName();
		$arrRouters=&$this->_arrRouters;
		$arrRouteInfo=array();
		if(isset($arrRouters[$sCurrentName])){
			if(!strpos($sCurrentName,'@')){
				$arrRouteInfo=$this->getNormalRoute($sCurrentName,$arrRouters[$sCurrentName]);
			}else{
				$arrRouteInfo=$this->getFlowRoute($sCurrentName,$arrRouters[$sCurrentName]);
			}
		}
		$this->_arrRouteInfo=$arrRouteInfo;
		return $this->_arrRouteInfo;
	}
	public function import(array $arrRouters=null){
		if(!$GLOBALS['_commonConfig_']['START_ROUTER']){
			return false;
		}
		if(is_null($arrRouters)){
			$arrRouters=$GLOBALS['_commonConfig_']['_ROUTER_'];
		}
		$this->_arrRouters=array_merge($this->_arrRouters,$arrRouters);
		return $this;
	}
	public function add($sRouteName,array $arrRule){
		$this->_arrRouters[$sRouteName]=$arrRule;
		return $this;
	}
	public function remove($sRouteName){
		unset($this->_arrRouters[$sRouteName]);
		return $this;
	}
	public function get($sRouteName){
		return $this->_arrRouters[$sRouteName];
	}
	public function getLastRouterName(){
		return $this->_sLastRouterName;
	}
	public function getLastRouterInfo(){
		 return $this->_arrLastRouteInfo;
	}
	private function parseUrl($Route){
		if(is_string($Route)){
			$arrArray=array_filter(explode('/',$Route));
		}else{
			$arrArray=$Route;
		}
		if(count($arrArray)!==2){
			Dyhb::E('$Route parameter format error,claiming the $arrArray the number of elements equal 2.');
		}
		$arrVar=array();
		$arrVar['a']=array_pop($arrArray);
		$arrVar['c']=array_pop($arrArray);
		return $arrVar;
	}
	private function getRouterName(){
		if(isset($_GET['r'])){
			$sRouteName=$_GET['r'];
			unset($_GET['r']);
		}else{
			$sPathInfo=&$_SERVER['PATH_INFO'];
			$arrPaths=explode($GLOBALS['_commonConfig_']['URL_PATHINFO_DEPR'],trim($sPathInfo,'/'));
			
			if(isset($arrPaths[0]) && $arrPaths[0]=='app'){
				array_shift($arrPaths);
				$_GET['app']=array_shift($arrPaths);
			}
			$sRouteName=array_shift($arrPaths);
		}
		$sRouteName=strtolower($sRouteName);
		if(isset($this->_arrRouters[$sRouteName.'@'])){
			$sRouteName=$sRouteName.'@';
		}
		$this->_sLastRouterName=$sRouteName;
		return $this->_sLastRouterName;
	}
	private function getNormalRoute($sRouteName,array $arrRule){
		if(isset($arrRule['regex'])){
			return $this->getRegexRoute_($sRouteName,$arrRule);
		}else{
			return $this->getSimpleRoute_($sRouteName,$arrRule);
		}
	}
	private function getFlowRoute($sRouteName,array $arrRule){
		foreach($arrRule as $arrRule){
			$arrVar=$this->getNormalRoute($sRouteName,$arrRule);
			if($arrVar!==null){
				return $arrVar;
			}
		}
		return array();
	}
	private function getSimpleRoute_($sRouteName,$arrRule){
		if(count($arrRule)<2 || count($arrRule)>5){
			Dyhb::E('$arrRule parameter must be greater than or equal 2,less than or equal 5.');
		}
		$arrVar=$this->parseUrl($arrRule[0]);
		if($GLOBALS['_commonConfig_']['URL_MODEL']===URL_COMMON){
			return $arrVar;
		}
		$sPathInfo=&$_SERVER['PATH_INFO'];
		$sDepr=$GLOBALS['_commonConfig_']['URL_PATHINFO_DEPR'];
		$sRegx=trim($sPathInfo,'/');
		$arrPaths=array_filter(explode($sDepr,trim(str_ireplace(strtolower($sRouteName),'',$sRegx),$sDepr)));
		if(isset($arrPaths[0]) && $arrPaths[0]=='app'){
			array_shift($arrPaths);
			$arrVar['app']=array_shift($arrPaths);
		}
		
		if(!empty($arrRule[1]) && in_array($arrRule[1],$arrPaths)){
			foreach($arrPaths as $nKey=>$sValue){
				if($sValue==$arrRule[1]){
					unset($arrPaths[$nKey]);
				}
			}
		}
		$arrVars=explode(',',$arrRule[1]);
		for($nI=0;$nI<count($arrVars);$nI++){
			$arrVar[$arrVars[$nI]]=array_shift($arrPaths);
		}
		$bResult=preg_replace('@(\w+)\/([^,\/]+)@e','$arrVar[\'\\1\']="\\2";',implode('/',$arrPaths));
		
		$arrParams=array();
		if(isset($arrRule[2])){
			parse_str($arrRule[2],$arrParams);
			$arrVar=array_merge($arrVar,$arrParams);
		}
		return $arrVar;
	}
	private function getRegexRoute_($sRouteName,$arrRule){
		if(count($arrRule)<3 || count($arrRule)>6){
			Dyhb::E('$arrRule parameter must be greater than or equal 3, less than or equal 6.');
		}
		$sPathInfo=&$_SERVER['PATH_INFO'];
		$sDepr=$GLOBALS['_commonConfig_']['URL_PATHINFO_DEPR'];
		$sRegx=trim($sPathInfo,'/');
		$sRegx=explode($sDepr,$sRegx);
		if($sRegx[0]=='app'){
			array_shift($sRegx);
			$_GET['app']=array_shift($sRegx);
		}
		$sRegx=implode($sDepr,$sRegx);
		$sRegx=ltrim($sRegx,strtolower(rtrim($sRouteName,'@')));
		$sTheRegex=array_shift($arrRule);
		$arrMatches=array();
		if(preg_match($sTheRegex,$sRegx,$arrMatches)){
			$arrVar=$this->parseUrl($arrRule[0]);
			if($GLOBALS['_commonConfig_']['URL_MODEL']===URL_COMMON){
				return $arrVar;
			}
			$arrVars=explode(',',$arrRule[1]);
			for($nI=0;$nI<count($arrVars);$nI++){
				$arrVar[$arrVars[$nI]]=$arrMatches[$nI+1];
			}
			$bResult=preg_replace('@(\w+)\/([^,\/]+)@e','$arrVar[\'\\1\']="\\2";',trim(str_replace($arrMatches[0],'',$sRegx),'\/'));
			
			$arrParams=array();
			if(isset($arrRule[2])){
				parse_str($arrRule[2],$arrParams);
				$arrVar=array_merge($arrVar,$arrParams);
			}
			return $arrVar;
		}
		return null;
	}
}