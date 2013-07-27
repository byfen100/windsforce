<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   公告首页($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	protected $_oHomehelpcategory=null;

	public function index(){
		$nEverynum=intval($GLOBALS['_option_']['baselistnum']);

		$nTotalRecord=AnnouncementModel::F()->all()->getCounts();

		$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));
		$arrAnnouncements=AnnouncementModel::F()->order('announcement_sort ASC,create_dateline DESC')->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrAnnouncements',$arrAnnouncements);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));

		$this->display('announcement+index');
	}

	public function index_title_(){
		return Dyhb::L('公告中心','Controller');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
