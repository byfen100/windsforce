<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   配置模型($)*/

!defined('DYHB_PATH') && exit;

class OptionModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'option',
			'props'=>array(
				'option_name'=>array('readonly'=>true),
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
		$oOptionModel=self::F('option_name=?',$sOptionName)->getOne();
		$oOptionModel->option_value=$sOptionValue;
		$oOptionModel->save(0,'update');

		if($oOptionModel->isError()){
			Dyhb::E($oOptionModel->getErrorMessage());
		}

		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('option');
	}

}
