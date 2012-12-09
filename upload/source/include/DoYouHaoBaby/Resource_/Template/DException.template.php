<?php
/* [DoYouHaoBaby!] (C)WindsForce Studio start this From 2010.10.04.
   系统异常模版($)*/

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

.footerlink{
	font:12px verdana,arial,sans-serif;
	margin-top:20px;
}

.red{
	color:red;
}
</style>
</head>
<body>
<div id="container">
	<div class="bodytitle">DoYouHaoBaby Exception</div>

	<div class="bodytext">
	The DoYouHaoBaby Framework occurs an exception. Please visite <a href="http://doyouhaobaby.net" target="_blank"><span class="red">DoYouHaoBaby.NET</span></a> for help.
	</div>
	
	<hr size="1"/>

	<?php if(isset($arrError['file'])){?>
	<div class="bodytext">Error Location:</div>
	<div class="bodytext">
		<ul>
			<li>FILE: <?php echo $arrError['file'];?></li>
			<li>LINE:<?php echo $arrError['line'];?></li>
		</li>
	</div>
	<?php }?>

	<div class="bodytext">Error Message:</div>
	<div class="bodytext" id="message">
		<ul><?php echo $arrError['message']; ?></ul>
	</div>

	<?php if(isset($arrError['trace'])) {?>
	<div class="bodytext">Trace Message:</div>
	<div class="bodytext">
		<ul><?php echo $arrError['trace'];?></ul>
	</div>
	<?php }?>

	<div class="footerlink">
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>">[<?php echo Dyhb::L( '重试','__DYHB__@Dyhb' );?>]</a>
		<a href="javascript:history.back();">[<?php echo Dyhb::L( '返回上一页','__DYHB__@Dyhb' );?>]</a>
		<a href="<?php if( defined('__APP__') ) { echo __APP__; } else{ echo '#'; } ?>">[<?php echo Dyhb::L( '返回首页','__DYHB__@Dyhb' );?>]</a>
	</div>

</div>
</body>
</html>