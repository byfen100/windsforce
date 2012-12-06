<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城商品相册模型($)*/

!defined('DYHB_PATH') && exit;

class ShopgoodsgalleryModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shopgoodsgallery',
			'props'=>array(
				'shopgoodsgallery_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shopgoodsgallery_id',
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
