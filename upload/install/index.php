<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   Needforbug 安装程序入口文件($)*/

//error_reporting(E_ERROR|E_PARSE|E_STRICT);
error_reporting(E_ALL);

/** Defined the version of needforbug */
define('NEEDFORBUG_SERVER_VERSION','1.0');
define('NEEDFORBUG_SERVER_RELEASE','20120104');

/** 系统应用路径定义 */
define('NEEDFORBUG_PATH',dirname(getcwd()));

/** 项目及项目路径定义 */
define('APP_NAME','install');
define('APP_PATH',getcwd());

/** 项目语言包路径定义 */
define('__COMMON_LANG__',NEEDFORBUG_PATH.'/ucontent/language');

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
