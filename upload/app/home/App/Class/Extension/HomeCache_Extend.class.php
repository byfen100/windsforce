<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   主页缓存文件($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HomeCache_Extend{

	public static function updateCacheOption(){
		$arrData=array();

		$arrOptionData=HomeoptionModel::F()->asArray()->all()->query();
		if(is_array($arrOptionData)){
			foreach($arrOptionData as $nKey=>$arrValue){
				$arrData[$arrValue['homeoption_name']]=$arrValue['homeoption_value'];
			}
		}

		Core_Extend::saveSyscache('home_option',$arrData);
	}

}
