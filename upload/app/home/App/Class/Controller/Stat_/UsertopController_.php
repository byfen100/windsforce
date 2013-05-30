<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   会员排行($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UsertopController extends Controller{

	public function index(){
		if(!Home_Extend::getVisiteallowed('siteusertop')){
			$this->E(Dyhb::L('你没有权限访问会员排行','Controller'));
		}

		// 读取统计缓存
		Core_Extend::loadCache('usertop');

		// 活跃用户
		$this->assign('arrActiveusers',$GLOBALS['_cache_']['usertop']['active']);
		
		// 最新加入会员
		$this->assign('arrNewusers',$GLOBALS['_cache_']['usertop']['new']);
		
		// 会员积分排行
		$this->assign('arrCreditusers',$GLOBALS['_cache_']['usertop']['credit']);
		
		// 会员粉丝排行
		$this->assign('arrFanusers',$GLOBALS['_cache_']['usertop']['fan']);
		
		// 会员在线时间
		$this->assign('arrOltimeusers',$GLOBALS['_cache_']['usertop']['oltime']);

		$this->display('stat+usertop');
	}

	public function usertop_title_(){
		return Dyhb::L('会员排行','Controller');
	}

	public function usertop_keywords_(){
		return $this->usertop_title_();
	}

	public function usertop_description_(){
		return $this->usertop_title_();
	}
	
}
