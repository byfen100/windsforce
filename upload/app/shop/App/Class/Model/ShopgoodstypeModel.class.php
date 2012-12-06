<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城商品类型模型($)*/

!defined('DYHB_PATH') && exit;

class ShopgoodstypeModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shopgoodstype',
			'props'=>array(
				'shopgoodstype_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shopgoodstype_id',
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
