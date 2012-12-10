<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   语言包缓存($)*/

!defined('DYHB_PATH') && exit;

class UpdateCacheLang{

	public static function cache(){
		$arrData=array();
		
		$arrLangs=G::listDir(WINDSFORCE_PATH.'/ucontent/language');
		if(is_array($arrLangs)){
			foreach($arrLangs as $sLang){
				$arrData[]=strtolower($sLang);
			}
		}
		
		Core_Extend::saveSyscache('lang', $arrData);
	}

}
