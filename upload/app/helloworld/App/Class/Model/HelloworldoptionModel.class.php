<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   配置模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HelloworldoptionModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'helloworldoption',
			'props'=>array(
				'helloworldoption_name'=>array('readonly'=>true),
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
		$oOptionModel=self::F('helloworldoption_name=?',$sOptionName)->getOne();
		$oOptionModel->helloworldoption_value=G::html($sOptionValue);
		$oOptionModel->save(0,'update');

		HelloworldCache_Extend::updateCache("option");
	}

}
