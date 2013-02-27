<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   缇ょ粍閰嶇疆妯″瀷($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GroupoptionModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'groupoption',
			'props'=>array(
				'groupoption_name'=>array('readonly'=>true),
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
		$oOptionModel=self::F('groupoption_name=?',$sOptionName)->getOne();
		$oOptionModel->groupoption_value=G::html($sOptionValue);
		$oOptionModel->save(0,'update');

		GroupCache_Extend::updateCache("option");
	}

}
