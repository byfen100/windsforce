<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   级别模型($)*/

!defined('DYHB_PATH') && exit;

class RatingModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'rating',
			'props'=>array(
				'rating_id'=>array('readonly'=>true),
				'ratinggroup'=>array(Db::BELONGS_TO=>'RatinggroupModel','target_key'=>'ratinggroup_id','skip_empty'=>true),
			),
			'attr_protected'=>'rating_id',
			'check'=>array(
				'rating_name'=>array(
					array('require',Dyhb::L('级别名不能为空','__COMMON_LANG__@Model/Rating')),
					array('max_length',50,Dyhb::L('级别名最大长度为50个字符','__COMMON_LANG__@Model/Rating')),
					array('uniqueRatingName',Dyhb::L('组名已经存在','__COMMON_LANG__@Model/Rating'),'condition'=>'must','extend'=>'callback'),
				),
			),
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}

	public function uniqueRatingName(){
		$nId=G::getGpc('id');

		$sRatingName=trim(G::getGpc('rating_name'));
		$sRatingInfo='';

		if($nId){
			$arrRating=self::F('rating_id=?',$nId)->asArray()->getOne();
			$sRatingInfo=trim($arrRating['rating_name']);
		}

		if($sRatingName !=$sRatingInfo){
			$arrResult=self::F()->getByrating_name($sRatingName)->toArray();
			if(!empty($arrResult['rating_id'])){
				return false;
			}else{
				return true;
			}
		}

		return true;
	}

	public function safeInput(){
		$_POST['rating_name']=G::html($_POST['rating_name']);
		$_POST['rating_nikename']=G::html($_POST['rating_nikename']);
		$_POST['rating_remark']=G::html($_POST['rating_remark']);
	}

}
