<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   后台公用初始化文件($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入WindsForce核心函数 */
require(WINDSFORCE_PATH.'/source/function/Core_Extend.class.php');

/** 导入WindsForce后台函数 */
require(WINDSFORCE_PATH.'/source/function/Admin_Extend.class.php');

/** 导入公用模型 */
Dyhb::import(WINDSFORCE_PATH.'/source/model');

/** 导入公用控制器 */
Dyhb::import(WINDSFORCE_PATH.'/source/controller');
