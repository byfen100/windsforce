<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商城基本配置文件($)*/

!defined('DYHB_PATH') && exit;

// 自动应用配置
$arrMyappConfigs=array();

// 读取前台应用基本配置
$arrFrontappconfigs=(array)require(WINDSFORCE_PATH.'/source/common/Config.php');

return array_merge($arrMyappConfigs,$arrFrontappconfigs);
