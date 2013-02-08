<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   用户栏目控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UserprofilesettingController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller/Common'));
		}
	}
	
	public function filter_(&$arrMap){
		$arrMap['userprofilesetting_id']=array('like',"%".G::getGpc('userprofilesetting_id')."%");
	}
	
	protected function aInsert($nId=null){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('userprofilesetting');
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

	protected function aResume($nId=null){
		$this->aInsert();
	}

}
