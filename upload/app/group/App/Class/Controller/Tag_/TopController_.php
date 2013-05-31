<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   标签排行($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TopController extends Controller{

	public function index(){
		$nGrouptopictagHotnum=$GLOBALS['_cache_']['group_option']['group_tag_hotnum'];

		if($nGrouptopictagHotnum<1){
			$nGrouptopictagHotnum=1;
		}
		
		// 获取排行数据
		$arrHourHotgrouptopictags=Groupdata_Extend::getGrouphotag($nGrouptopictagHotnum,'3600');// 一小时排行
		
		$arrTodayHotgrouptopictags=Groupdata_Extend::getGrouphotag($nGrouptopictagHotnum,'86400');// 今日排行
		
		$arrWeekHotgrouptopictags=Groupdata_Extend::getGrouphotag($nGrouptopictagHotnum,'604800');// 本周排行
		
		$arrMonthHotgrouptopictags=Groupdata_Extend::getGrouphotag($nGrouptopictagHotnum,'2592000');// 当月排行
		
		$arrYearHotgrouptopictags=Groupdata_Extend::getGrouphotag($nGrouptopictagHotnum,'31536000');// 年度排行
		
		$arrTotalHotgrouptopictags=GrouptopictagModel::F()->order('grouptopictag_count DESC')->limit(0,$nGrouptopictagHotnum)->getAll();// 总排行
		
		// 赋值
		$this->assign('arrHourHotgrouptopictags',$arrHourHotgrouptopictags);
		$this->assign('arrTodayHotgrouptopictags',$arrTodayHotgrouptopictags);
		$this->assign('arrWeekHotgrouptopictags',$arrWeekHotgrouptopictags);
		$this->assign('arrMonthHotgrouptopictags',$arrMonthHotgrouptopictags);
		$this->assign('arrYearHotgrouptopictags',$arrYearHotgrouptopictags);
		$this->assign('arrTotalHotgrouptopictags',$arrTotalHotgrouptopictags);
		
		$this->display('tag+top');
	}

	public function top_title_(){
		return Dyhb::L('标签排行榜','Controller');
	}

	public function top_keywords_(){
		return $this->top_title_();
	}

	public function top_description_(){
		return $this->top_title_();
	}

}
