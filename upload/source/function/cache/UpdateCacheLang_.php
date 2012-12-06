<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   语言包缓存($)*/

!defined('DYHB_PATH') && exit;

class UpdateCacheLang{

	public static function cache(){
		$arrData=array();
		
		$arrLangs=G::listDir(NEEDFORBUG_PATH.'/ucontent/language');
		if(is_array($arrLangs)){
			foreach($arrLangs as $sLang){
				$arrData[]=strtolower($sLang);
			}
		}
		
		Core_Extend::saveSyscache('lang', $arrData);
	}

}
