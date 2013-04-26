<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   语言包检测工具($Liu.XiangMin)*/

/** 防止乱码 */
header("Content-type:text/html;charset=utf-8");

/** 
 * 由于使用google翻译后的语言包，如果被直接复制到编辑器，很可能出现不可见字符
 * 不可见字符将会导致PHP错误，所以我们开发的时候遍历一下系统的语言包来修正
 * 默认简体中文不用检测
 */

/**
 * 遍历文件夹函数
 */
function listFile($sDir){
	$arrList=scandir($sDir);

	if(is_array($arrList)){
		foreach($arrList as $sFile){
			$sFilelocation=$sDir."/".$sFile;//生成路径

			if(is_dir($sFilelocation) && $sFile!="." && $sFile!=".."){
				listFile($sFilelocation);
			}elseif($sFile!="." && $sFile!=".." && !in_array($sFile,array('index.html','LICENSE.dyhb','Tips.dyhb'))){
				include($sFilelocation);
			}
		}
	}
}

/** 基本路径 */
$sLanguage='Zh-tw';
$sBasedirpath=dirname(__FILE__).'/../upload/';

/** 系统所有语言包 */
$arrFiledirpath=array(
	/*
	// 应用语言包
	'ucontent/language/'.$sLanguage,
	'app/home/App/Lang/'.$sLanguage,
	'app/wap/App/Lang/'.$sLanguage,
	'app/helloworld/App/Lang/'.$sLanguage,
	'app/group/App/Lang/'.$sLanguage,
	'app/home/App/Lang/Admin/'.$sLanguage,
	'app/wap/App/Lang/Admin/'.$sLanguage,
	'app/helloworld/App/Lang/Admin/'.$sLanguage,
	'app/group/App/Lang/Admin/'.$sLanguage,
	'admin/App/Lang/'.$sLanguage,
	*/

	/*
	// 框架语言包
	'source/include/DoYouHaoBaby/Resource_/Lang/'.$sLanguage,
	'source/include/Tools/Tools_/App/Lang/'.$sLanguage,
	'source/include/Tools/Tools_/Websetup/websetup/App/Lang/'.$sLanguage,
	*/
);

foreach($arrFiledirpath as $sFiledirpath){
	$sFiledirpath=$sBasedirpath.'/'.$sFiledirpath;
	listFile($sFiledirpath);
}

echo '恭喜，语言包没有发现任何错误!';
