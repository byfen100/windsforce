<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Helloworld卸载清理程序($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

// 如果应用不需要清理数据，你可以删除本文件
$sSql=<<<EOF

DROP TABLE IF EXISTS {WINDSFORCE}helloworldoption;

EOF;

$this->runQuery($sSql);

$bFinish=TRUE;
