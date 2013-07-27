<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   积分记录操作信息模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CreditoperationModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'creditoperation',
			'props'=>array(
				'creditoperation_name'=>array('readonly'=>true),
			),
			'attr_protected'=>'creditoperation_name',
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
