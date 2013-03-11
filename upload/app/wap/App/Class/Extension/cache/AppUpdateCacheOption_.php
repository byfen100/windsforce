<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   wap应用配置缓存($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AppUpdateCacheOption{

	public static function cache(){
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
