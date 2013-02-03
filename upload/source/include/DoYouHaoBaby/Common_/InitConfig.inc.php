<?php
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   初始化基本配置($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

if(!is_file(APP_RUNTIME_PATH.'/Config.php')){
	require(DYHB_PATH.'/Common_/AppConfig.inc.php');
}
$GLOBALS['_commonConfig_']=Dyhb::C((array)(include APP_RUNTIME_PATH.'/Config.php'));
