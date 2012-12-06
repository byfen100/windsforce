<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   Needforbug公用路由配置文件($)*/

!defined('DYHB_PATH') && exit;

/** 公用路由是为了解决跨应用生成URL */
return array(
	'space'=>array('space/index','id'),
	'fresh'=>array('ucenter/view','id'),
	'help'=>array('homehelp/show','id'),
	'file'=>array('attachment/show','id'),
);
