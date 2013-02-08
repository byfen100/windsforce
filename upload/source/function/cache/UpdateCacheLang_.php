<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   语言包缓存($Liu.XiangMin)*/

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
