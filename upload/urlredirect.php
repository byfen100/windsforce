<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   Needforbug URL跳转($)*/

/** 处理URL */
$sUrl=isset($_GET['go'])?$_GET['go']:'';
$sUrl=urldecode(trim($sUrl));
$sUrl=str_replace(array("%2F","%3D","%3F","&amp;"),array('/','=','?','&'),$sUrl);

/** 跳转 */
if(!empty($sUrl)){
	header("Location:{$sUrl}");
}else{
	echo 'Url is empty!';
}

exit();
