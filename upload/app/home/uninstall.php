<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   主页卸载清理程序($)*/

!defined('DYHB_PATH') && exit;

/**
// 本程序用于卸载完应用后的清理工作
// 如果应用不需要清理数据，你可以删除本文件
$sSql=<<<EOF

DROP TABLE IF EXISTS {NEEDFORBUG}hello;

EOF;

$this->runQuery($sSql);
*/

$bFinish=TRUE;
