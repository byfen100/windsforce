<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   我发起的活动列表($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		$arrWhere=array();

		// 活动
		$arrWhere['user_id']=$GLOBALS['___login___']['user_id'];

		$nTotaleventnum=EventModel::F()->where($arrWhere)->all()->getCounts();

		$oPage=Page::RUN($nTotaleventnum,12,G::getGpc('page','G'));

		$arrEvents=EventModel::F()->where($arrWhere)->order("event_status ASC,create_dateline DESC")->limit($oPage->returnPageStart(),12)->getAll();

		$this->assign('arrEvents',$arrEvents);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));

		$this->display('ucenterevent+index');
	}

	public function index_title_(){
		return Dyhb::L('活动用户中心','Controller');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
