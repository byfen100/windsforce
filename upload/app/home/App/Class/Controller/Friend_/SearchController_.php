<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   好友搜索($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SearchController extends Controller{

	public function index(){
		if($GLOBALS['_option_']['allow_search_user']==0){
			$this->E(Dyhb::L('系统关闭了好友搜索功能','Controller/Friend'));
		}
		
		require_once(Core_Extend::includeFile('function/Profile_Extend'));
		
		Core_Extend::loadCache('userprofilesetting');
		$this->assign('arrUserprofilesettingDatas',$GLOBALS['_cache_']['userprofilesetting']);
		
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

		// 视图
		$arrProfileSetting=Profile_Extend::getProfileSetting();

		$this->assign('arrBases',$arrProfileSetting[0]);
		$this->assign('arrContacts',$arrProfileSetting[1]);
		$this->assign('arrEdus',$arrProfileSetting[2]);
		$this->assign('arrWorks',$arrProfileSetting[3]);
		$this->assign('arrInfos',$arrProfileSetting[4]);

		$arrInfoMenus=Profile_Extend::getInfoMenu();
		$this->assign('arrInfoMenus',$arrInfoMenus);
		
		$this->display('friend+search');
	}

	public function search_title_(){
		return Dyhb::L('查找好友','Controller/Friend');
	}

	public function search_keywords_(){
		return $this->search_title_();
	}

	public function search_description_(){
		return $this->search_title_();
	}

}
