<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   群组缓存文件($)*/

!defined('DYHB_PATH') && exit;

class GroupCache_Extend{

	public static function updateCacheOption(){
		$arrData=array();

		$arrOptionData=GroupoptionModel::F()->asArray()->all()->query();
		if(is_array($arrOptionData)){
			foreach($arrOptionData as $nKey=>$arrValue){
				$arrData[$arrValue['groupoption_name']]=$arrValue['groupoption_value'];
			}
		}

		Core_Extend::saveSyscache('group_option',$arrData);
	}

}
