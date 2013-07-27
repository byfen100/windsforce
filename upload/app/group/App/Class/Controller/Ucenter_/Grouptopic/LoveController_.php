<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   我喜欢的话题列表($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class LoveController extends Controller{

	public function index(){
		$nEverynum=$GLOBALS['_cache_']['group_option']['group_ucenter_listtopicnum'];

		// 喜欢的帖子
		$nTotalGrouptopicnum=GrouptopicloveModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->all()->getCounts();

		$oPage=Page::RUN($nTotalGrouptopicnum,$nEverynum,G::getGpc('page','G'));

		$arrGrouptopicloves=GrouptopicloveModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->limit($oPage->returnPageStart(),$nEverynum)->order('create_dateline DESC')->getAll();

		$this->assign('arrGrouptopicloves',$arrGrouptopicloves);
		$this->assign('nEverynum',$nEverynum);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));

		$this->display('ucentergrouptopic+love');
	}

	public function lovetopic_title_(){
		return Dyhb::L('我喜欢的话题','Controller');
	}

	public function lovetopic_keywords_(){
		return $this->lovetopic_title_();
	}

	public function lovetopic_description_(){
		return $this->lovetopic_title_();
	}

}
