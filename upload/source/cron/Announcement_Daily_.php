<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   ��ʱ������վ���棨Ĭ��ÿ��0ʱ0�֣�($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** ������վ���ڹ������� */
$nDeletenum=Dyhb::instance('AnnouncementModel')->deleteAllEndtime(CURRENT_TIMESTAMP);

/** ���»��� */
if(!Dyhb::classExists('Cache_Extend')){
	require_once(Core_Extend::includeFile('function/Cache_Extend'));
}
Cache_Extend::updateCache('announcement');
