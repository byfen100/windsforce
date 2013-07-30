<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商城App配置缓存($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AppUpdateCacheOption{

	public static function cache(){
		$arrData=array();

		$arrOptionData=ShopoptionModel::F()->asArray()->all()->query();
		if(is_array($arrOptionData)){
			foreach($arrOptionData as $nKey=>$arrValue){
				$arrData[$arrValue['shopoption_name']]=$arrValue['shopoption_value'];
			}
		}

		Core_Extend::saveSyscache('shop_option',$arrData);
	}

}
