<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商城收货人地址信息模型($)*/

!defined('DYHB_PATH') && exit;

class ShopaddressModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shopaddress',
			'props'=>array(
				'shopaddress_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shopaddress_id',
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
