<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   Needforbug 后台入口文件($)*/

/** 是否需要安装 */
if(!is_file('data/Install.lock.php')){
	header("location:install/index.php");
}

//error_reporting(E_ERROR|E_PARSE|E_STRICT);
error_reporting(E_ALL);
define('NEEDFORBUG_DEBUG',TRUE);

/** Defined the version of needforbug */
define('NEEDFORBUG_SERVER_VERSION','1.0');
define('NEEDFORBUG_SERVER_RELEASE','20120104');

/** 系统应用路径定义 */
define('NEEDFORBUG_PATH',getcwd());

/** 项目及项目路径定义 */
define('APP_NAME','admin');
define('APP_PATH',NEEDFORBUG_PATH.'/'.APP_NAME);

/** 项目运行时路径及数据库表缓存路径 */
define('APP_RUNTIME_PATH',NEEDFORBUG_PATH.'/data/~runtime/app/'.APP_NAME);
define('DB_META_CACHED_PATH',NEEDFORBUG_PATH.'/data/~runtime/cache_/field');

/** 项目语言包路径定义 */
define('__COMMON_LANG__',NEEDFORBUG_PATH.'/ucontent/language');

/** 项目模板路径定义 */
define('__STATICS__','admin/Static');
define('__THEMES__','admin/Theme');

/** 项目编译锁定文件定义 */
define('APP_RUNTIME_LOCK',NEEDFORBUG_PATH.'/source/protected/~Runtime.inc.lock');

/** 加载框架编译版本 */
//define('STRIP_RUNTIME_SPACE',false);
define('DYHB_THIN',true);

/** 去掉模板空格 */
define('TMPL_STRIP_SPACE',true);

/** 载入框架 */
require(NEEDFORBUG_PATH.'/source/include/DoYouHaoBaby/~DoYouHaoBaby.php');
App::RUN();
