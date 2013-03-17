<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   积分规则记录模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CreditrulelogModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'creditrulelog',
			'props'=>array(
				'creditrulelog_id'=>array('readonly'=>true),
				'creditrule'=>array(Db::HAS_ONE=>'CreditruleModel','source_key'=>'creditrule_id','target_key'=>'creditrule_id'),
				'user'=>array(Db::HAS_ONE=>'UserModel','source_key'=>'user_id','target_key'=>'user_id'),
			),
			'attr_protected'=>'creditrulelog_id',
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
	
	public function increase($nCreditrulelog,$arrLog){
		if($nCreditrulelog && !empty($arrLog) && is_array($arrLog)){
			$oDb=Db::RUN();

			$sSql="UPDATE ".self::F()->query()->getTablePrefix()."creditrulelog SET ".implode(',',$arrLog)." WHERE `creditrulelog_id`=".$nCreditrulelog;
			
			$oDb->query($sSql);
			
			return true;
		}

		return false;
	}

}
