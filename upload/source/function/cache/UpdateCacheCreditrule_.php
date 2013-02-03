<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   积分等级缓存($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UpdateCacheCreditrule{

	public static function cache(){
		$arrData=array();

		$arrCreditruls=CreditruleModel::F()->asArray()->getAll();
		if(is_array($arrCreditruls)){
			foreach($arrCreditruls as $arrRule){
				$arrRule['creditrule_rulenameuni']=urlencode($arrRule['creditrule_name']);
				$arrData[$arrRule['creditrule_action']]=$arrRule;
			}
		}

		Core_Extend::saveSyscache('creditrule',$arrData);
	}

}
