<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   公告显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ShowController extends GlobalchildController{

	protected $_oAnnouncement=null;
	
	public function index(){
		$nId=intval(G::getGpc('id','G'));

		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定公告ID','Controller'));
		}

		$oAnnouncement=AnnouncementModel::F('announcement_id=?',$nId)->getOne();
		if(!empty($oAnnouncement['announcement_id'])){
			$this->assign('oAnnouncement',$oAnnouncement);
			
			$this->_oAnnouncement=$oAnnouncement;

			$this->display('announcement+show');
		}else{
			$this->E(Dyhb::L('你指定的公告不存在','Controller'));
		}
	}

	public function show_title_(){
		return $this->_oAnnouncement['announcement_title'];
	}

	public function show_description_(){
		return $this->show_title_();
	}

	public function show_keywords_(){
		return $this->show_title_();
	}

}
