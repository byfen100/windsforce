<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Helloworld初始化安装程序($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

// 初始化应用表数据
$sSql=<<<EOF

DROP TABLE IF EXISTS {WINDSFORCE}helloworldoption;
CREATE TABLE {WINDSFORCE}helloworldoption (
  `helloworldoption_name` varchar(32) NOT NULL DEFAULT '' COMMENT '名字',
  `helloworldoption_value` text NOT NULL COMMENT '值',
  PRIMARY KEY (`helloworldoption_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

EOF;

$this->runQuery($sSql);

$bFinish=TRUE;
