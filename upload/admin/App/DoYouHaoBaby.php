<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   后台公用初始化文件($)*/

!defined('DYHB_PATH') && exit;

/** 导入Needforbug核心函数 */
require(NEEDFORBUG_PATH.'/source/function/Core_Extend.class.php');

/** 导入Needforbug后台函数 */
require(NEEDFORBUG_PATH.'/source/function/Admin_Extend.class.php');

/** 导入公用模型 */
Dyhb::import(NEEDFORBUG_PATH.'/source/model');

/** 导入公用控制器 */
Dyhb::import(NEEDFORBUG_PATH.'/source/controller');
