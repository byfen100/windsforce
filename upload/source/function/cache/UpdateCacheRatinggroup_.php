<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   等级分组缓存($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UpdateCacheRatinggroup{

	public static function cache(){
		$arrData=array();
		
		$arrRatinggroupDatas=RatinggroupModel::F('ratinggroup_status=?',1)->order('ratinggroup_id ASC')->asArray()->all()->query();
		if(is_array($arrRatinggroupDatas)){
			foreach($arrRatinggroupDatas as $arrRatinggroup){
				$arrData[$arrRatinggroup['ratinggroup_id']]=$arrRatinggroup;
			}
		}
		
		Core_Extend::saveSyscache('ratinggroup',$arrData);
	}

}
