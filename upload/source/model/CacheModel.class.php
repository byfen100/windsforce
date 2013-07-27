<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   通用缓存模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CacheModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'cache',
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}

	public function insertCache($sName,$sData){
		$oDb=Db::RUN();
		$oDb->query("INSERT INTO ".self::F()->query()->getTablePrefix()."cache value('{$sName}','".$sData."','".CURRENT_TIMESTAMP."','');");
	}

}
