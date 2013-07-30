<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商城配送方式模型($)*/

!defined('DYHB_PATH') && exit;

class ShopshippingModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shopshipping',
			'props'=>array(
				'shopshipping_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shopshipping_id',
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
