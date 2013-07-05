<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动首页控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		// 活动类型赋值，根据活动类型来取得活动
		$arrEventcategorys=EventcategoryModel::F('eventcategory_parentid=?',0)->order('eventcategory_sort ASC')->getAll();
		
		$this->assign('arrEventcategorys',$arrEventcategorys);

		$this->display('public+index');
	}

	public function get_childEventcategory($nParentid){
		$arrEventcategorys=EventcategoryModel::F()->where(array('eventcategory_parentid'=>$nParentid))->getAll();
		
		return $arrEventcategorys;
	}

	public function index_title_(){
		if($GLOBALS['_commonConfig_']['DEFAULT_APP']!='event'){
			return Dyhb::L('活动','Controller');
		}
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}


}
