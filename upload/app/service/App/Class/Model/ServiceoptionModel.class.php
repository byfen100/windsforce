<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   服务配置模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ServiceoptionModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'serviceoption',
			'props'=>array(
				'serviceoption_name'=>array('readonly'=>true),
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

	public static function uploadOption($sOptionName,$sOptionValue){
		$oOptionModel=self::F('serviceoption_name=?',$sOptionName)->getOne();
		$oOptionModel->serviceoption_value=G::html($sOptionValue);
		$oOptionModel->save(0,'update');

		if($oOptionModel->isError()){
			Dyhb::E($oOptionModel->getErrorMessage());
		}

		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('service_option');
	}

}
