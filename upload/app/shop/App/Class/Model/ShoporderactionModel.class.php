<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   订单操作记录模型($)*/

!defined('DYHB_PATH') && exit;

class ShoporderactionModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shoporderaction',
			'props'=>array(
				'shoporderaction_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shoporderaction_id',
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
