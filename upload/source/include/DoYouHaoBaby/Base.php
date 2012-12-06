<?php
/* [DoYouHaoBaby!] (C)Dianniu From 2010.
   DoYouHaoBaby 框架基础文件($)*/

/** 防止乱码 */
header("Content-type:text/html;charset=utf-8");

/** DoYouHaoBaby系统目录定义 */
//[RUNTIME]
if(!defined('DYHB_PATH')){
//[/RUNTIME]
	define('DYHB_PATH',str_replace('\\','/',dirname(__FILE__)));
//[RUNTIME]
}
//[/RUNTIME]

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

//[RUNTIME]
/** 系统核心编译文件 */
if(is_file(DYHB_PATH.'/~DoYouHaoBaby.php')){
	require_once(DYHB_PATH.'/~DoYouHaoBaby.php');
}else{
	require_once(DYHB_PATH.'/Common_/InitRuntime.inc.php');
}
//[/RUNTIME]
