<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   我参加的活动列表($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class JoinController extends Controller{

	public function index(){
		// 活动
		$nTotalEventnum=EventuserModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->all()->getCounts();

		$oPage=Page::RUN($nTotalEventnum,12,G::getGpc('page','G'));

		$arrEventusers=EventuserModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->limit($oPage->returnPageStart(),12)->order('eventuser_status ASC,create_dateline DESC')->getAll();

		$this->assign('arrEventusers',$arrEventusers);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));

		$this->display('ucenterevent+join');
	}

	public function join_title_(){
		return Dyhb::L('我参加的活动','Controller');
	}

	public function join_keywords_(){
		return $this->join_title_();
	}

	public function join_description_(){
		return $this->join_title_();
	}

}
