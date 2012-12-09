<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   系统短消息删除状态模型($)*/

!defined('DYHB_PATH') && exit;

class PmsystemdeleteModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'pmsystemdelete',
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
