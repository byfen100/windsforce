<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   前台基本配置文件($)*/

!defined('DYHB_PATH') && exit;

// 自定义应用配置
$arrMyappConfigs=array();

// 读取前台应用基本配置
$arrFrontappconfigs=(array)require(NEEDFORBUG_PATH.'/source/common/Config.php');

return array_merge($arrMyappConfigs,$arrFrontappconfigs);
