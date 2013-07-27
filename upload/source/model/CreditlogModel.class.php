<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   积分记录模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CreditlogModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'creditlog',
			'props'=>array(
				'creditlog_id'=>array('readonly'=>true),
				'creditoperation'=>array(Db::HAS_ONE=>'CreditoperationModel','source_key'=>'creditlog_operation','target_key'=>'creditoperation_name'),
				'related'=>array(Db::HAS_ONE=>'UserModel','source_key'=>'creditlog_relatedid','target_key'=>'user_id'),
				'user'=>array(Db::HAS_ONE=>'UserModel','source_key'=>'user_id','target_key'=>'user_id'),
			),
			'attr_protected'=>'creditlog_id',
			'check'=>array(
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

	public function insert($arrData){
		$oCreditlog=new self($arrData);
		$oCreditlog->save(0);

		if($oCreditlog->isError()){
			$this->setErrorMessage($oCreditlog->getErrorMessage());
			return false;
		}

		return true;
	}

}
