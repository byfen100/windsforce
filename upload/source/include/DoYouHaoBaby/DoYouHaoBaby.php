<?php
/* [DoYouHaoBaby!] (C)小牛哥Dyhb From 2010.10.04.
   DoYouHaoBaby 入口文件($)*/

/** DoYouHaoBaby系统目录定义 */
define('DYHB_PATH',str_replace('\\','/',dirname(__FILE__)));

if(defined('DYHB_THIN') && DYHB_THIN===true){
	if(is_file(DYHB_PATH.'/~DoYouHaoBaby.php')){
		exit('Please load the ~DoYouHaoBaby.php instead of DoYouHaoBaby.php');
	}else{
		require_once(DYHB_PATH.'/Base.php');
	}

	return;
}

/** 直接加载DoYouHaoBaby基础文件 */
require_once(DYHB_PATH.'/Base.php');
