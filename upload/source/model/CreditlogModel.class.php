<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   积分记录模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CreditlogModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'creditlog',
			'props'=>array(
				'creditlog_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'creditlog_id',
			'check'=>array(
			),
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
