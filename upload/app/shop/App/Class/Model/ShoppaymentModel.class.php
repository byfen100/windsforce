<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城支付方式模型($)*/

!defined('DYHB_PATH') && exit;

class ShoppaymentModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shoppayment',
			'props'=>array(
				'shoppayment_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shoppayment_id',
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
