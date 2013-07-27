<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   WindsForce Zend Check($Liu.XiangMin)*/

!defined('IN_API') && exit;

if(phpversion()>='5.3'){
	include WINDSFORCE_PATH.'/api/zend/Zendcheck53.php';
}else{
	include WINDSFORCE_PATH.'/api/zend/Zendcheck52.php';
}
