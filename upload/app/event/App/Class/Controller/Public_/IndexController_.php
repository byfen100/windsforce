<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动首页控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		// 读取活动列表
		$arrWhere['event_status']=1;

		$nEverynum=12;
			
		$nTotalRecord=EventModel::F()->where($arrWhere)->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));

		$arrEvents=EventModel::F()->where($arrWhere)->order('create_dateline DESC')->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrEvents',$arrEvents);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		
		// 活动类型赋值，根据活动类型来取得活动
		$arrEventcategorys=EventcategoryModel::F('eventcategory_parentid=?',0)->order('eventcategory_sort ASC')->getAll();
		
		$this->assign('arrEventcategorys',$arrEventcategorys);

		// 热门活动
		$arrHotevents=EventModel::F()->where($arrWhere)->order('event_attentioncount DESC')->limit(0,4)->getAll();

		$this->assign('arrHotevents',$arrHotevents);

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
