<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   网站公告控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AnnouncementController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['announcement_title']=array('like',"%".G::getGpc('announcement_title')."%");
	}

	protected function AInsertObject_($oModel){
		$oModel->timeFormat();
		$oModel->safeInput();
	}
	
	protected function AUpdateObject_($oModel){
		$this->AInsertObject_($oModel);
	}

	protected function aInsert($nId=null){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('announcement');
	}

	protected function aUpdate($nId=null){
		$this->aInsert();
	}

	public function aForeverdelete($sId){
		$this->aInsert();
	}
	
	public function afterInputChangeAjax($sName=null){
		$this->aInsert();
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			if(!$this->check_admin($nId)){
				$this->E(Dyhb::L('你无法删除别人的公告','Controller'));
			}
		}
	}

	public function check_admin($nId){
		if($GLOBALS['___login___']['user_id']==1){
			return true;
		}

		$nId=intval($nId);

		$oAnnouncement=AnnouncementModel::F('announcement_id=?',$nId)->getOne();
		if(empty($oAnnouncement['announcement_id'])){
			return false;
		}

		if($GLOBALS['___login___']['user_name']!=$oAnnouncement['announcement_username']){
			return false;
		}

		return true;
	}

}
