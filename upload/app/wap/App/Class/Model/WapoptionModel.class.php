<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   配置模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class WapoptionModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'wapoption',
			'props'=>array(
				'wapoption_name'=>array('readonly'=>true),
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
		$oOptionModel=self::F('wapoption_name=?',$sOptionName)->getOne();
		$oOptionModel->wapoption_value=G::html($sOptionValue);
		$oOptionModel->save(0,'update');

		WapCache_Extend::updateCache("option");
	}

}
