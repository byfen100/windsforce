<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   Wap初始化安装程序($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/**
// 这里仅为需要数据库或者其它初始化数据所使用
// 如果应用不需要初始化一些数据，你可以删除本文件
$sSql=<<<EOF

DROP TABLE IF EXISTS {WINDSFORCE}hello;
CREATE TABLE {WINDSFORCE}hello (
  `test_id` int(10) NOT NULL auto_increment COMMENT '测试ID',
  `test_value` varchar(50) character set utf8 NOT NULL COMMENT '测试效果',
  PRIMARY KEY  (`test_id`)
) TYPE=MyISAM;

EOF;

$this->runQuery($sSql);
*/

$bFinish=TRUE;
