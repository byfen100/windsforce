<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   Needforbug 附件地址($)*/

/** 处理URL */
$sUrl=http_build_query($_GET);
$sUrl='index.php?app=home&c=attachmentread&a=index'.($sUrl?'&'.$sUrl:'');

/** 跳转 */
header("Location:{$sUrl}");

exit();
