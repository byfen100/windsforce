<?php
/* [DoYouHaoBaby!] (C)WindsForce Team Start This From 2010.10.04.
   语言包($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class LangPackageForClient{

	static public function getLangPackage($sLangName,$sPackageName){
		$oThePackage=LangPackage::getPackage($sLangName,$sPackageName);
		if(!$oThePackage){
			return;
		}

		header('Content-type: text/html; charset=utf-8');

		// 向客户端传送语言包内容
		echo '{';
		$nLine=0;
		foreach($oThePackage->LANGS as $key=>$sLang){
			if($nLine++){
				echo ',';
			}
			echo '"'.$key.'"'.':'.'"'.$sLang.'"';
		}
		echo '}';
	}

	static public function setNewSentence($sSentenceKey,$sSentence,$sLangName,$sPackageName){
		$thePackage=LangPackage::getPackage($sLangName,$sPackageName);
		if(!$thePackage){
			echo(sprintf('can not find lang:%s,package:%s.','LibDyhb',$sLangName,$sPackageName));
			return;
		}

		$thePackage->set($sSentenceKey,$sSentence);
		print '1';
	}

}
