<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城文章评论模型($)*/

!defined('DYHB_PATH') && exit;

class ShoparticlecommentModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shoparticlecomment',
			'props'=>array(
				'shoparticlecomment_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shoparticlecomment_id',
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
