<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   前台公用初始化文件($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入WindsForce核心函数 */
require(WINDSFORCE_PATH.'/source/function/Core_Extend.class.php');

/** 导入公用模型 */
Dyhb::import(WINDSFORCE_PATH.'/source/model');

/** 导入公用控制器 */
Dyhb::import(WINDSFORCE_PATH.'/source/controller');

/** 定义应用的语言包 */
if(APP_NAME!=='wap'){
	define('__APP'.strtoupper(APP_NAME).'_COMMON_LANG__',WINDSFORCE_PATH.'/app/'.APP_NAME.'/App/Lang/Admin');
}else{
	define('__APPHOME_COMMON_LANG__',WINDSFORCE_PATH.'/app/home/App/Lang/Admin');
}
define('__APP'.strtoupper(APP_NAME).'_APP_LANG__',WINDSFORCE_PATH.'/app/'.APP_NAME.'/App/Lang');


/** 导入WindsForce应用模板函数 */
require(WINDSFORCE_PATH.'/source/function/Apptheme_Extend.class.php');

/** 定义应用的公用主题目录 */
define('__UTHEME__',__ROOT__.'/ucontent/theme/'.TEMPLATE_NAME);
define('__UTHEMEPUB__',__ROOT__.'/ucontent/theme/'.TEMPLATE_NAME.'/Public');

/** 定义应用的公用消息图片目录 */
if(TEMPLATE_NAME==='default' || !is_file(WINDSFORCE_PATH.'/ucontent/theme/'.TEMPLATE_NAME.'/Public/Images/loader.gif')){
	define('__MESSAGE_IMG_PATH__',__ROOT__.'/ucontent/theme/Default/Public/Images');
}else{
	define('__MESSAGE_IMG_PATH__',__ROOT__.'/ucontent/theme/'.TEMPLATE_NAME.'/Public/Images');
}
