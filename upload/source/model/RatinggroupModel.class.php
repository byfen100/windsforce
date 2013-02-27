<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   级别分组模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class RatinggroupModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'ratinggroup',
			'props'=>array(
				'ratinggroup_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'ratinggroup_id',
			'check'=>array(
				'rating_name'=>array(
					array('require',Dyhb::L('组名不能为空','__COMMON_LANG__@Model/Ratinggroup')),
					array('english',Dyhb::L('组名只能为英文字符','__COMMON_LANG__@Model/Ratinggroup')),
					array('max_length',50,Dyhb::L('组名最大长度为50个字符','__COMMON_LANG__@Model/Ratinggroup')),
					array('ratinggroupName',Dyhb::L('组名已经存在','__COMMON_LANG__@Model/Ratinggroup'),'condition'=>'must','extend'=>'callback'),
				),
				'ratinggroup_title'=>array(
					array('require',Dyhb::L('组显示名不能为空','__COMMON_LANG__@Model/Ratinggroup')),
					array('max_length',50,Dyhb::L('组显示名最大长度为50个字符','__COMMON_LANG__@Model/Ratinggroup')),
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

	public function ratinggroupName(){
		$nId=G::getGpc('id','P');

		$sRatinggroupName=G::getGpc('ratinggroup_name','P');
		$sRatinggroupInfo='';

		if($nId){
			$arrRatinggroup=self::F('ratinggroup_id=?',$nId)->asArray()->getOne();
			$sRatinggroupInfo=trim($arrRatinggroup['ratinggroup_name']);
		}

		if($sRatinggroupName !=$sRatinggroupInfo){
			$arrResult=self::F()->getByratinggroup_name($sRatinggroupName)->toArray();
			if(!empty($arrResult['ratinggroup_id'])){
				return false;
			}else{
				return true;
			}
		}

		return true;
	}

	public function safeInput(){
		if(isset($_POST['ratinggroup_name'])){
			$_POST['ratinggroup_name']=G::html($_POST['ratinggroup_name']);
		}

		$_POST['ratinggroup_title']=G::html($_POST['ratinggroup_title']);
	}

}
