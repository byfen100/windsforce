<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   后台管理员消息模型($)*/

!defined('DYHB_PATH') && exit;

class AdminmessageModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'adminmessage',
			'props'=>array(
				'adminmessage_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'adminmessage_id',
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
