<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   积分相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Credit_Extend{
	
	public static function getCreditType($nCycleType){
		switch($nCycleType){
			case 0:
				return Dyhb::L('一次','__COMMON_LANG__@Function/Credit_Extend');
				break;
			case 1:
				return Dyhb::L('每天','__COMMON_LANG__@Function/Credit_Extend');
				break;
			case 2:
				return Dyhb::L('整点','__COMMON_LANG__@Function/Credit_Extend');
				break;
			case 3:
				return Dyhb::L('间隔分钟','__COMMON_LANG__@Function/Credit_Extend');
				break;
			case 4:
				return Dyhb::L('不限','__COMMON_LANG__@Function/Credit_Extend');
				break;
		}
	}

	public static function getAvailableExtendCredits(){
		$arrAvailableExtendCredits=array();

		$arrExtendCredits=unserialize($GLOBALS['_option_']['extend_credit']);
		foreach($arrExtendCredits as $nKey=>$arrExtendCredit){
			if($arrExtendCredit['available']==1){
				$arrAvailableExtendCredits[$nKey]=$arrExtendCredit;
			}
		}

		return $arrAvailableExtendCredits;
	}

	public static function checkCredit($nCreditNum){
		return ($nCreditNum>=-99 && $nCreditNum<=99);
	}

}
