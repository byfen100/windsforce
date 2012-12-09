<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   Needforbug 附件地址($)*/

/** 处理URL */
$sUrl=http_build_query($_GET);
$sUrl='index.php?app=home&c=attachmentread&a=index'.($sUrl?'&'.$sUrl:'');

/** 跳转 */
header("Location:{$sUrl}");

exit();
