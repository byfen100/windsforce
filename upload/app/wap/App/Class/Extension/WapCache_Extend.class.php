<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   Wap缓存文件($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class WapCache_Extend{

	public static function updateCacheOption(){
		$arrData=array();

		$arrOptionData=WapoptionModel::F()->asArray()->all()->query();
		if(is_array($arrOptionData)){
			foreach($arrOptionData as $nKey=>$arrValue){
				$arrData[$arrValue['wapoption_name']]=$arrValue['wapoption_value'];
			}
		}

		Core_Extend::saveSyscache('wap_option',$arrData);
	}

}
