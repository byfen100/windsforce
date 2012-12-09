<?php
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   系统错误模版($)*/

!defined('DYHB_PATH') && exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>DoYouHaoBaby <?php echo Dyhb::L('系统消息','__DYHB__@Dyhb');?></title>
<style type="text/css">
body {
	background-color:white;
	color:black;
	word-break:break-all;
}

a:link,a:visited{
	font:8pt/11pt verdana,arial,sans-serif;
	color:#999999;
	text-decoration:none;
}

a:hover{
	text-decoration:underline;
}

#container{
	width:650px;
}

#message{
	width:600px;
	color:black;
	background-color:#FFFFCC;
	margin:10px 0px;
	padding-right:35px;
	border:#DFDFDF 1px solid;
}

.bodytitle{
	font:20px verdana,arial,sans-serif; 
	color:red;
	padding:10px auto;
	height:35px; 
}

.bodytext{
	font:8pt/11pt verdana,arial,sans-serif;
}

.bodytext a:hover{
	text-decoration:none;
}

.red{
	color:red;
}
</style>
</head>
<body>
<div id="container">
	<div class="bodytitle">DoYouHaoBaby Need Exit</div>

	<div class="bodytext">
	The DoYouHaoBaby Framework occurs a fatal error. Please visite <a href="http://doyouhaobaby.net" target="_blank"><span class="red">DoYouHaoBaby.NET</span></a> for help.
	</div>
	
	<hr size="1"/>

	<div class="bodytext">Error Message:</div>
	<div class="bodytext" id="message">
		<ul><?php echo $sMessage; ?></ul>
	</div>

</div>
</body>
</html>