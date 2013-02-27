<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   访问推广模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class PromotionModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'promotion',
			'autofill'=>array(
				array('promotion_ip','getIp','create','callback'),
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

	protected function getIp(){
		return G::getIp();
	}

}
