<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   等级缓存($)*/

!defined('DYHB_PATH') && exit;

class UpdateCacheRating{

	public static function cache(){
		$arrData=array();

		$arrRatingDatas=RatingModel::F()->order('rating_id ASC')->asArray()->all()->query();
		if(is_array($arrRatingDatas)){
			foreach($arrRatingDatas as &$arrRating){
				$arrRating['rating_originalname']=$arrRating['rating_name'];
				$arrRating['rating_name']=$arrRating['rating_nikename']?$arrRating['rating_nikename']:$arrRating['rating_name'];
				$arrRating['rating_icon']=__PUBLIC__.'/images/rating/'.$GLOBALS['_option_']['rating_icontype'].'/'.$arrRating['rating_icon'];
				$arrData[$arrRating['rating_id']]=$arrRating;
			}
		}
		
		Core_Extend::saveSyscache('rating',$arrData);
	}

}
