<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   ������������Ĭ��ÿ��0ʱ0�֣�($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** ���뻺����� */
if(!Dyhb::classExists('Cache_Extend')){
	require_once(Core_Extend::includeFile('function/Cache_Extend'));
}

/** ÿ�ո����û��������� */
Cache_Extend::updateCache('usertop');
