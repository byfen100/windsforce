<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   前台路由配置文件($)*/

!defined('DYHB_PATH') && exit;

// 自定义路由配置
$arrMyappRouters=array(
	'goods'=>array('shopgoods/view','id'),
);

// 读取前台应用基本路由配置
$arrFrontapprouters=(array)require(NEEDFORBUG_PATH.'/source/common/Router.php');

return array_merge($arrMyappRouters,$arrFrontapprouters);
