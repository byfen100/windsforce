<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Service应用配置缓存($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AppUpdateCacheOption{

	public static function cache(){
		$arrData=array();

		$arrOptionData=ServiceoptionModel::F()->asArray()->all()->query();
		if(is_array($arrOptionData)){
			foreach($arrOptionData as $nKey=>$arrValue){
				$arrData[$arrValue['serviceoption_name']]=$arrValue['serviceoption_value'];
			}
		}

		Core_Extend::saveSyscache('service_option',$arrData);
	}

}
