<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   前台公用初始化文件($)*/

!defined('DYHB_PATH') && exit;

/** 导入Needforbug核心函数 */
require(NEEDFORBUG_PATH.'/source/function/Core_Extend.class.php');

/** 导入公用模型 */
Dyhb::import(NEEDFORBUG_PATH.'/source/model');

/** 导入公用控制器 */
Dyhb::import(NEEDFORBUG_PATH.'/source/controller');

/** 定义应用的语言包 */
define('__APP_ADMIN_LANG__',NEEDFORBUG_PATH.'/app/'.APP_NAME.'/App/Lang/Admin');

/** 定义应用的公用主题目录 */
define('__UTHEME__',__ROOT__.'/ucontent/theme/'.TEMPLATE_NAME);
define('__UTHEMEPUB__',__ROOT__.'/ucontent/theme/'.TEMPLATE_NAME.'/Public');

/** 定义应用的公用消息图片目录 */
if(TEMPLATE_NAME==='default' || !is_file(NEEDFORBUG_PATH.'/ucontent/theme/'.TEMPLATE_NAME.'/Public/Images/loader.gif')){
	define('__MESSAGE_IMG_PATH__',__ROOT__.'/ucontent/theme/Default/Public/Images');
}else{
	define('__MESSAGE_IMG_PATH__',__ROOT__.'/ucontent/theme/'.TEMPLATE_NAME.'/Public/Images');
}
