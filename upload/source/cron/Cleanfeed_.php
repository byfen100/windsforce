<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   定时清理过期动态（默认每日0时0分）($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 清理网站过期动态数据 */
Dyhb::instance('FeedModel')->deleteAllCreatedateline($GLOBALS['_option_']['feed_keep_time']);
