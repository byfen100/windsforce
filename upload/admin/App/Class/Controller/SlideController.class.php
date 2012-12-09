<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   骞荤伅鐗囨帶鍒跺櫒($)*/

!defined('DYHB_PATH') && exit;

class SlideController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['slide_title']=array('like',"%".G::getGpc('slide_title')."%");
	}

	public function bIndex_(){
		$arrOptionData=$GLOBALS['_option_'];

		$this->assign('arrOptions',$arrOptionData);
	}

	public function update_option(){
		$arrOptions=G::getGpc('options','P');
		$nSlideduration=$arrOptions['slide_duration'];
		$nSlideDelay=intval($arrOptions['slide_delay']);

		if($nSlideduration<0.1 || $nSlideduration>1){
			$_POST['options']['slide_duration']=0.3;
		}

		if($nSlideDelay<1){
			$_POST['options']['slide_delay']=5;
		}

		$oOptionController=new OptionController();

		$oOptionController->update_option();
	}

	public function bEdit_(){
		$nId=intval(G::getGpc('id','G'));

		if($this->is_system_slide($nId)){
			$this->E(Dyhb::L('绯荤粺骞荤伅鐗囨棤娉曠紪杈�,'Controller/Slide'));
		}
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			if($this->is_system_slide($nId)){
				$this->E(Dyhb::L('绯荤粺骞荤伅鐗囨棤娉曞垹闄�,'Controller/Slide'));
			}
		}
	}
	
	protected function aInsert($nId=null){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache("slide");
	}

	public function afterInputChangeAjax($sName=null){
		$this->aInsert();
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

	public function is_system_slide($nId){
		$nId=intval($nId);

		if($nId<=3){
			return true;
		}

		return false;
	}

}
