<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   配置缓存($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UpdateCacheOption{

	public static function cache(){
		$arrData=array();

		$arrOptionData=OptionModel::F()->asArray()->all()->query();
		if(is_array($arrOptionData)){
			foreach($arrOptionData as $nKey=>$arrValue){
				$arrData[$arrValue['option_name']]=$arrValue['option_value'];
			}
		}

		Core_Extend::saveSyscache('option',$arrData);
	}

}
