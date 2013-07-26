<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Helloworld基本配置文件($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

// 自定义应用配置
$arrMyappConfigs=array();

// 读取前台应用基本配置
$arrFrontappconfigs=(array)require(WINDSFORCE_PATH.'/source/common/Config.php');

// 应用菜单
$arrFrontappconfigs['APP_MENU']=array(
	'event://ucenter/index'=>'我发起的活动',
	'event://ucenter/join'=>'我参加的活动',
	'event://ucenter/attention'=>'我感兴趣的活动',
);

return array_merge($arrMyappConfigs,$arrFrontappconfigs);
