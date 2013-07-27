<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组基本配置文件($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

// 自定义应用配置
$arrMyappConfigs=array();

// 读取前台应用基本配置
$arrFrontappconfigs=(array)require(WINDSFORCE_PATH.'/source/common/Config.php');

// 应用菜单
$arrFrontappconfigs['APP_MENU']=array(
	'group://ucenter/index'=>'小组个人中心',
	'group://ucenter/lovetopic'=>'我喜欢的帖子',
);

return array_merge($arrMyappConfigs,$arrFrontappconfigs);
