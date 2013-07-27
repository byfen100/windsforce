<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   今日数据清理（默认每日0时0分）($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入缓存组件 */
if(!Dyhb::classExists('Cache_Extend')){
	require_once(Core_Extend::includeFile('function/Cache_Extend'));
}

/** 每日更新用户排行数据 */
Cache_Extend::updateCache('usertop');

/** 清理网站近日发布数量 */
OptionModel::uploadOption('todayusernum',0);
OptionModel::uploadOption('todaytotalnum',0);
OptionModel::uploadOption('todayhomefreshnum',0);
OptionModel::uploadOption('todayhomefreshcommentnum',0);
OptionModel::uploadOption('todayattachmentnum',0);

/** 清空网站登陆记录数据 */
Dyhb::instance('LoginlogModel')->deleteAll();

/** 清理网站管理记录数据 */
Dyhb::instance('AdminlogModel')->deleteAll();

/** 清理网站过期提醒数据 */
Dyhb::instance('NoticeModel')->deleteAllCreatedateline($GLOBALS['_option_']['notice_keep_time']);
