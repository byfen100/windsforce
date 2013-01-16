<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   好友搜索($)*/

!defined('DYHB_PATH') && exit;

class SearchController extends Controller{

	public function index(){
		require_once(Core_Extend::includeFile('function/Profile_Extend'));
		
		// 时间计算
		$nNowYear=date('Y',CURRENT_TIMESTAMP);
		$nNowMonth=date('m',CURRENT_TIMESTAMP);
		if(in_array($nNowMonth,array(1,3,5,7,8,10,12))){
			$nDays=31;
		}elseif(in_array($nNowMonth,array(4,6,9,11))){
			$nDays=30;
		}elseif($nNowYear &&
			(($nNowYear%400==0) || ($nNowYear%4==0 && $nNowYear%400!=0))
		){
			$nDays=29;
		}else{
			$nDays=28;
		}

		$this->assign('nNowYear',$nNowYear);
		$this->assign('nNowDays',$nDays);
		$this->assign('sDirthDistrict',Profile_Extend::getDistrict(array(),'birth'));
		$this->assign('sResideDistrict',Profile_Extend::getDistrict(array(),'reside'));
		
		$this->display('friend+search');
	}

}
