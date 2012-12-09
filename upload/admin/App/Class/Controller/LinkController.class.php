<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   鍙嬫儏琛旀帴鎺у埗鍣�$)*/

!defined('DYHB_PATH') && exit;

class LinkController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['link_name']=array('like',"%".G::getGpc('link_name')."%");
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

}
