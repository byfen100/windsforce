<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   商城商品属性值模型($)*/

!defined('DYHB_PATH') && exit;

class ShopattributevalueModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shopattributevalue',
			'props'=>array(
				'shopattributevalue_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shopattributevalue_id',
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
