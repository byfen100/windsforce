<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   定时清理网站访问推广数据（默认每日0时0分）($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 清理网站访问推广数据 */
Dyhb::instance('PromotionModel')->deleteAll();
