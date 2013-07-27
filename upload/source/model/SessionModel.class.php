<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   会话模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SessionModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'session',
			'autofill'=>array(
				array('session_ip','getIp','create','callback'),
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
