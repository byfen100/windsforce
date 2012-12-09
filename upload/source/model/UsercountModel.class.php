<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   用户统计模型($)*/

!defined('DYHB_PATH') && exit;

class UsercountModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'usercount',
			'props'=>array(
				'user'=>array(Db::HAS_ONE=>'UserModel','source_key'=>'user_id','target_key'=>'user_id'),
			),
			'attr_protected'=>'user_id',
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

	public function increase($nUserId,$arrCredit){
		$arrSql=array();

		$arrAllowkey=array('usercount_extendcredit1','usercount_extendcredit2','usercount_extendcredit3',
			'usercount_extendcredit4','usercount_extendcredit5','usercount_extendcredit6','usercount_extendcredit7',
			'usercount_extendcredit8','usercount_friends','usercount_oltime','usercount_fans');

		foreach($arrCredit as $sKey=>$value){
			if(($value=intval($value)) && $value && in_array($sKey,$arrAllowkey)){
				$arrSql[]="`{$sKey}`=`{$sKey}`+'{$value}'";
			}
		}

		if(!empty($arrSql)){
			$oDb=Db::RUN();

			$sSql="UPDATE ".self::F()->query()->getTablePrefix()."usercount SET ".implode(',',$arrSql)." WHERE user_id IN (".implode(',',$nUserId).")";
			
			$oDb->query($sSql);
		}
	}

}
