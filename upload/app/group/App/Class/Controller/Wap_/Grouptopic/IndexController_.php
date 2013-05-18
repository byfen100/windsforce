<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组Wap首页控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		// 读取帖子列表
		$arrWhere=array();

		$nEverynum=$GLOBALS['_option_']['wap_baselist_num'];
		$arrWhere['grouptopic_status']=1;
		$arrWhere['grouptopic_isaudit']=1;

		$nTotalRecord=GrouptopicModel::F()->where($arrWhere)->all()->getCounts();
		
		$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));
		
		$arrGrouptopics=GrouptopicModel::F()->where($arrWhere)->order("grouptopic_update DESC,create_dateline DESC")->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrGrouptopics',$arrGrouptopics);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));

		$this->display('wap+index');
	}

	public function index_title_(){
		return Dyhb::L('Wap小组','Controller/Wap');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
