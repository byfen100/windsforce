<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   Wap基本配置文件($)*/

!defined('DYHB_PATH') && exit;

// 自定义应用配置
$arrMyappConfigs=array(
	'PHP_OFF'=>TRUE,
	'DEFAULT_CONTROL'=>'public',
	'TMPL_ACTION_ERROR'=>'message',
	'TMPL_ACTION_SUCCESS'=>'message',
);

// 读取全局配置
$arrGlobalConfig=(array)require(NEEDFORBUG_PATH.'/config/Config.inc.php');

// 关闭调试
$arrGlobalConfig['SHOW_RUN_TIME']=FALSE;
$arrGlobalConfig['SHOW_DB_TIMES']=FALSE;
$arrGlobalConfig['SHOW_GZIP_STATUS']=FALSE;

// 使用普通URL模式
$arrGlobalConfig['URL_MODEL']=0;

return array_merge($arrMyappConfigs,$arrGlobalConfig);
