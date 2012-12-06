<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   后台管理员操作记录模型($)*/

!defined('DYHB_PATH') && exit;

class AdminlogModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'adminlog',
			'props'=>array(
				'adminlog_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'adminlog_id',
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
