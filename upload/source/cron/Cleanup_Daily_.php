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

/** ������վ���շ������� */
OptionModel::uploadOption('todayusernum',0);
OptionModel::uploadOption('todaytotalnum',0);
OptionModel::uploadOption('todayhomefreshnum',0);
OptionModel::uploadOption('todayhomefreshcommentnum',0);
OptionModel::uploadOption('todayattachmentnum',0);

/** �����վ��½��¼���� */
Dyhb::instance('LoginlogModel')->deleteAll();

/** ������վ�����¼���� */
Dyhb::instance('AdminlogModel')->deleteAll();

/** ������վ������������ */
Dyhb::instance('NoticeModel')->deleteAllCreatedateline($GLOBALS['_option_']['notice_keep_time']);
