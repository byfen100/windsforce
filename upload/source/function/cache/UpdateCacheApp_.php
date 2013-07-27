<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   应用缓存($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UpdateCacheApp{

	public static function cache(){
		$arrData=array();

		$arrApps=AppModel::F('app_status=?',1)->order('app_id DESC')->getAll();
		if(is_array($arrApps)){
			foreach($arrApps as $oApp){
				$arrData[]=$oApp['app_identifier'];
			}
		}

		Core_Extend::saveSyscache('app',$arrData);
	}

}
