<?php
/* [DoYouHaoBaby!] (C)Dianniu From 2010.
   全局控制器($)*/

!defined('DYHB_PATH') && exit;

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
