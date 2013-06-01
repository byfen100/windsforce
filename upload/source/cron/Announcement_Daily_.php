<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   定时清理网站公告（默认每日0时0分）($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 清理网站过期公告数据 */
$nDeletenum=Dyhb::instance('AnnouncementModel')->deleteAllEndtime(CURRENT_TIMESTAMP);

/** 更新缓存 */
if(!Dyhb::classExists('Cache_Extend')){
	require_once(Core_Extend::includeFile('function/Cache_Extend'));
}
Cache_Extend::updateCache('announcement');
