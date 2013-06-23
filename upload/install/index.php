<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   WindsForce 安装程序入口文件($Liu.XiangMin)*/

error_reporting(E_ERROR|E_PARSE|E_STRICT);
//error_reporting(E_ALL);
//define('DOYOUHAOBABY_DEBUG',TRUE);

/** Defined the version of windsforce */
define('WINDSFORCE_SERVER_VERSION','1.1.1');
define('WINDSFORCE_SERVER_RELEASE','20130623');
define('WINDSFORCE_SERVER_BUG','1.0');

/** 系统应用路径定义 */
define('WINDSFORCE_PATH',dirname(getcwd()));

/** 项目及项目路径定义 */
define('APP_NAME','install');
define('APP_PATH',getcwd());

/** 项目语言包路径定义 */
define('__COMMON_LANG__',WINDSFORCE_PATH.'/ucontent/language');

/** 项目编译锁定文件定义 */
define('APP_RUNTIME_LOCK',WINDSFORCE_PATH.'/source/protected/~Runtime.inc.lock');

/** 加载框架编译版本 */
//define('STRIP_RUNTIME_SPACE',false);
define('DYHB_THIN',true);

/** 去掉模板空格 */
define('TMPL_STRIP_SPACE',true);

/** 载入框架 */
require(WINDSFORCE_PATH.'/source/include/DoYouHaoBaby/~DoYouHaoBaby.php');
App::RUN();
