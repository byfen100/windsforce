<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   Helloworld缓存文件($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HelloworldCache_Extend{

	public static function updateCacheOption(){
		$arrData=array();

		$arrOptionData=HelloworldoptionModel::F()->asArray()->all()->query();
		if(is_array($arrOptionData)){
			foreach($arrOptionData as $nKey=>$arrValue){
				$arrData[$arrValue['helloworldoption_name']]=$arrValue['helloworldoption_value'];
			}
		}

		Core_Extend::saveSyscache('helloworld_option',$arrData);
	}

}
