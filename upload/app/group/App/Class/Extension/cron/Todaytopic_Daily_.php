<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   主题今日发帖清理操作（默认每日0时0分）($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 清空小组 */
Dyhb::instance('GroupModel')->clearToday();

/** 清理配置 */
GroupoptionModel::uploadOption('group_topictodaynum',0);
GroupoptionModel::uploadOption('group_topiccommenttodaynum',0);
GroupoptionModel::uploadOption('group_totaltodaynum',0);
