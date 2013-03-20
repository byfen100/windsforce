<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   新鲜事话题排行列表($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TagsController extends Controller{

	public function index(){
		$nHomefreshtagHotnum=$GLOBALS['_cache_']['home_option']['homefreshtag_hot_num'];

		if($nHomefreshtagHotnum<1){
			$nHomefreshtagHotnum=1;
		}
		
		
		// 获取排行数据
		$arrHourHothomefreshtags=Homefresh_Extend::getHomefreshtagBydate('3600',$nHomefreshtagHotnum);// 一小时排行
		
		$arrTodayHothomefreshtags=Homefresh_Extend::getHomefreshtagBydate('86400',$nHomefreshtagHotnum);// 今日排行
		
		$arrWeekHothomefreshtags=Homefresh_Extend::getHomefreshtagBydate('604800',$nHomefreshtagHotnum);// 本周排行
		
		$arrMonthHothomefreshtags=Homefresh_Extend::getHomefreshtagBydate('2592000',$nHomefreshtagHotnum);// 当月排行
		
		$arrYearHothomefreshtags=Homefresh_Extend::getHomefreshtagBydate('31536000',$nHomefreshtagHotnum);// 年度排行
		
		$arrTotalHothomefreshtags=HomefreshtagModel::F('homefreshtag_status=?',1)->order('homefreshtag_totalcount DESC')->limit(0,$nHomefreshtagHotnum)->getAll();// 总排行
		
		// 赋值
		$this->assign('arrHourHothomefreshtags',$arrHourHothomefreshtags);
		$this->assign('arrTodayHothomefreshtags',$arrTodayHothomefreshtags);
		$this->assign('arrWeekHothomefreshtags',$arrWeekHothomefreshtags);
		$this->assign('arrMonthHothomefreshtags',$arrMonthHothomefreshtags);
		$this->assign('arrYearHothomefreshtags',$arrYearHothomefreshtags);
		$this->assign('arrTotalHothomefreshtags',$arrTotalHothomefreshtags);
		
		$this->display('homefreshtag+tags');
	}

	public function tags_title_(){
		return Dyhb::L('新鲜事话题排行榜','Controller/Homefreshtag');
	}

	public function tags_keywords_(){
		return $this->tags_title_();
	}

	public function tags_description_(){
		return $this->tags_title_();
	}

}
