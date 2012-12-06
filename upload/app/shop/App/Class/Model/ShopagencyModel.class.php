<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   办事处模型($)*/

!defined('DYHB_PATH') && exit;

class ShopagencyModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shopagency',
			'props'=>array(
				'shopagency_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shopagency_id',
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
