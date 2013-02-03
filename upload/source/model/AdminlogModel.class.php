<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   后台管理员操作记录模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AdminlogModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'adminlog',
			'props'=>array(
				'adminlog_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'adminlog_id',
			'autofill'=>array(
				array('user_id','userId','create','callback'),
				array('adminlog_username','userName','create','callback'),
				array('adminlog_ip','getIp','create','callback'),
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

	protected function userId(){
		$arrUserData=$GLOBALS['___login___'];

		return $arrUserData['user_id'];
	}

	protected function userName(){
		$arrUserData=$GLOBALS['___login___'];

		return $arrUserData['user_name'];
	}

	protected function getIp(){
		return G::getIp();
	}

}
