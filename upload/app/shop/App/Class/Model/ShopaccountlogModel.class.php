<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户账目日志表模型($)*/

!defined('DYHB_PATH') && exit;

class ShopaccountlogModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shopaccountlog',
			'props'=>array(
				'shopaccountlog_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shopaccountlog_id',
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
