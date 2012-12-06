<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   群组与群组分类索引模型($)*/

!defined('DYHB_PATH') && exit;

class GroupcategoryindexModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'groupcategoryindex',
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
