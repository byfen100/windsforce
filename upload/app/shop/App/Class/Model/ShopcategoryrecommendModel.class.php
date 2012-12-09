<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   商城商品分类推荐模型($)*/

!defined('DYHB_PATH') && exit;

class ShopcategoryrecommendModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shopcategoryrecommend',
			'props'=>array(
				'shopcategoryrecommend_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shopcategoryrecommend_id',
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
