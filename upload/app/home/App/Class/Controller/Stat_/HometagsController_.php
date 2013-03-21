<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户广场用户标签排行榜列表($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HometagsController extends Controller{

	public function index(){
		if(!Home_Extend::getVisiteallowed('siteuserlist')){
			$this->E(Dyhb::L('你没有权限访问用户标签排行榜','Controller/Stat'));
		}

		$nHometagHotnum=$GLOBALS['_cache_']['home_option']['hometag_hot_num'];

		if($nHometagHotnum<1){
			$nHometagHotnum=1;
		}
		
		// 获取排行数据
		$arrHourHothometags=Home_Extend::getHometagBydate('3600',$nHometagHotnum);// 一小时排行
		
		$arrTodayHothometags=Home_Extend::getHometagBydate('86400',$nHometagHotnum);// 今日排行
		
		$arrWeekHothometags=Home_Extend::getHometagBydate('604800',$nHometagHotnum);// 本周排行
		
		$arrMonthHothometags=Home_Extend::getHometagBydate('2592000',$nHometagHotnum);// 当月排行
		
		$arrYearHothometags=Home_Extend::getHometagBydate('31536000',$nHometagHotnum);// 年度排行
		
		$arrTotalHothometags=HometagModel::F()->order('hometag_count DESC')->limit(0,$nHometagHotnum)->getAll();// 总排行
		
		// 赋值
		$this->assign('arrHourHothometags',$arrHourHothometags);
		$this->assign('arrTodayHothometags',$arrTodayHothometags);
		$this->assign('arrWeekHothometags',$arrWeekHothometags);
		$this->assign('arrMonthHothometags',$arrMonthHothometags);
		$this->assign('arrYearHothometags',$arrYearHothometags);
		$this->assign('arrTotalHothometags',$arrTotalHothometags);

		$this->display('stat+hometags');
	}

	public function hometags_title_(){
		return Dyhb::L('用户标签排行榜','Controller/Stat');
	}

	public function hometags_keywords_(){
		return $this->hometags_title_();
	}

	public function hometags_description_(){
		return $this->hometags_title_();
	}
	
}
