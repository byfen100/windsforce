<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
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
