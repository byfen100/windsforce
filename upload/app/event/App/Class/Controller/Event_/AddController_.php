<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加活动控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AddController extends Controller{

	public function index(){
		if($GLOBALS['_cache_']['event_option']['front_add']==0){
			$this->E(Dyhb::L('系统关闭了创建活动功能','Controller'));
		}
		
		// 活动类型
		$oEventcategoryTree=Dyhb::instance('EventcategoryModel')->getEventcategoryTree();
		$this->assign('oEventcategoryTree',$oEventcategoryTree);
		
		$this->display('event+add');
	}

	public function add_title_(){
		return Dyhb::L('发起活动','Controller');
	}

	public function add_keywords_(){
		return $this->add_title_();
	}

	public function add_description_(){
		return $this->add_title_();
	}

}
