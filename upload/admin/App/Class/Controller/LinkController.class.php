<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   友情链接控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class LinkController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller'));
		}
	}
	
	public function filter_(&$arrMap){
		$arrMap['link_name']=array('like',"%".G::getGpc('link_name')."%");
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			if($this->is_system_link($nId)){
				$this->E(Dyhb::L('系统链接无法删除','Controller'));
			}
		}
	}

	public function bEdit_(){
		$nId=intval(G::getGpc('id','G'));

		if($this->is_system_link($nId)){
			$this->E(Dyhb::L('系统链接无法编辑','Controller'));
		}
	}

	protected function aInsert($nId=null){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('link');
	}

	protected function aUpdate($nId=null){
		$this->aInsert();
	}

	public function aForeverdelete($sId){
		$this->aInsert();
	}

	protected function aForbid(){
		$this->aInsert();
	}
	
	protected function aResume(){
		$this->aInsert();
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function is_system_link($nId){
		$nId=intval($nId);

		$oLink=LinkModel::F('link_id=?',$nId)->getOne();
		if(empty($oLink['link_id'])){
			return false;
		}

		if($oLink['link_issystem']==1){
			return true;
		}

		return false;
	}

}
