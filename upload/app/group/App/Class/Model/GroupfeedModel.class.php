<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   用户群组动态模型($)*/

!defined('DYHB_PATH') && exit;

class GroupfeedModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'groupfeed',
			'props'=>array(
				'groupfeed_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'groupfeed_id',
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
