<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   社会化帐号控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SociatypeController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['sociatype_title']=array('like',"%".G::getGpc('sociatype_title')."%");
	}

	protected function aInsert($nId=null){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache("sociatype");
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

}
