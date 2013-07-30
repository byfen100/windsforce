<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商城文章模型($)*/

!defined('DYHB_PATH') && exit;

class ShoparticleModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shoparticle',
			'props'=>array(
				'shoparticle_id'=>array('readonly'=>true),
				'shoparticlecategory'=>array(Db::BELONGS_TO=>'ShoparticlecategoryModel','source_key'=>'shoparticlecategory_id','target_key'=>'shoparticlecategory_id'),
			),
			'attr_protected'=>'shoparticle_id',
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}

}
