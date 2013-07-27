<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   WindsForce 附件地址($Liu.XiangMin)*/

/** 处理URL */
$sUrl=http_build_query($_GET);
$sUrl='index.php?app=home&c=attachmentread&a=index'.($sUrl?'&'.$sUrl:'');

/** 跳转 */
header("Location:{$sUrl}");

exit();
