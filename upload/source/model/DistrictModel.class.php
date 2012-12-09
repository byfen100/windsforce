<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   地区模型($)*/

!defined('DYHB_PATH') && exit;

class DistrictModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'district',
			'check'=>array(
				'district_name'=>array(
					array('require',Dyhb::L('地区名字不能为空','__COMMON_LANG__@Model/District')),
				),
				'district_sort'=>array(
					array('number',Dyhb::L('序号只能是数字','__COMMON_LANG__@Model/Common'),'condition'=>'notempty','extend'=>'regex'),
				)
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

	public function getDistrictByUpid($Upid,$sSort='DESC'){
		$Upid=is_array($Upid)?array_map('intval',(array)$Upid):intval($Upid);

		if($Upid!==null){
			return DistrictModel::F()->where(array('district_upid'=>array('in',$Upid)))->order("district_sort {$sSort}")->asArray()->getAll();
		}

		return array();
	}

	public function safeInput(){
		$_POST['district_name']=G::html($_POST['district_name']);
	}

}
