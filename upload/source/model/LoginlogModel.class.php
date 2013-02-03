<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   登录状态记录模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class LoginlogModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'loginlog',
			'props'=>array(
				'loginlog_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'loginlog_id',
			'autofill'=>array(
				array('loginlog_ip','getIp','create','callback'),
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
