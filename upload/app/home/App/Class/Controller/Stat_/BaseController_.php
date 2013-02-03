<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   站点基本信息($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class BaseController extends Controller{

	public function index(){
		if(!Home_Extend::getVisiteallowed('sitebase')){
			$this->E(Dyhb::L('你没有权限访问基本概况','Controller/Stat'));
		}
		
		Core_Extend::loadCache('site');
		$this->assign('arrSite',$GLOBALS['_cache_']['site']);

		$this->display('stat+base');
	}

	public function index_title_(){
		return Dyhb::L('基本概况','Controller/Stat');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}
	
}
