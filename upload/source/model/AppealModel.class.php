<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   申诉信息模型($)*/

!defined('DYHB_PATH') && exit;

class AppealModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'appeal',
			'props'=>array(
				'appeal_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'appeal_id',
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
