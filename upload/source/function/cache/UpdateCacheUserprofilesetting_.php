<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   个人信息配置缓存($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UpdateCacheUserprofilesetting{

	public static function cache(){
		$arrUserprofilesettingDatas=array();

		$arrUserprofilesettings=UserprofilesettingModel::F('userprofilesetting_status=?',1)->asArray()->getAll();
		if(is_array($arrUserprofilesettings)){
			foreach($arrUserprofilesettings as $arrUserprofilesetting){
				$arrUserprofilesettingDatas[$arrUserprofilesetting['userprofilesetting_id']]=$arrUserprofilesetting;
			}
		}

		Core_Extend::saveSyscache('userprofilesetting',$arrUserprofilesettingDatas);
	}

}
