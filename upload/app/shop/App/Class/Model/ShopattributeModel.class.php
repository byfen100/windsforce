<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商城商品类型属性模型($)*/

!defined('DYHB_PATH') && exit;

class ShopattributeModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shopattribute',
			'props'=>array(
				'shopattribute_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shopattribute_id',
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
