<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商城商品模型($)*/

!defined('DYHB_PATH') && exit;

class ShopgoodsModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shopgoods',
			'props'=>array(
				'shopgoods_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shopgoods_id',
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
