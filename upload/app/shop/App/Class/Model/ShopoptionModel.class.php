<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城配置模型($)*/

!defined('DYHB_PATH') && exit;

class ShopoptionModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shopoption',
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
