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
