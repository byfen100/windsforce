<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   标签列表($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		// 读取标签列表
		$nEverynum=$GLOBALS['_cache_']['group_option']['group_tag_listnum'];

		$nTotalGrouptopictagnum=GrouptopictagModel::F()->all()->getCounts();

		$oPage=Page::RUN($nTotalGrouptopictagnum,$nEverynum,G::getGpc('page','G'));

		$arrGrouptopictags=GrouptopictagModel::F()->order("create_dateline DESC")->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrGrouptopictags',$arrGrouptopictags);
		$this->assign('nEverynum',$nEverynum);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		
		$this->display('tag+index');
	}

	public function index_title_(){
		return Dyhb::L('标签列表','Controller');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
