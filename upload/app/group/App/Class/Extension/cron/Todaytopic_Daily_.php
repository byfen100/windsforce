<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   主题今日发帖清理操作，也包括小组日常清理（默认每日0时0分）($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入小组模型 */
if(APP_NAME!=='group'){
	Dyhb::import(WINDSFORCE_PATH.'/app/group/App/Class/Model');
	define('__APPGROUP_COMMON_LANG__',WINDSFORCE_PATH.'/app/group/App/Lang/Admin');// 定义语言包
}

/** 清空小组 */
Dyhb::instance('GroupModel')->clearToday();

/** 清理配置 */
GroupoptionModel::uploadOption('group_topictodaynum',0);
GroupoptionModel::uploadOption('group_topiccommenttodaynum',0);
GroupoptionModel::uploadOption('group_totaltodaynum',0);

/** 清空帖子搜索记录 */
Dyhb::instance('GroupsearchindexModel')->deleteAll();
