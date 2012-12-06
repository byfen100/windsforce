<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   等级分组控制器($)*/

!defined('DYHB_PATH') && exit;

class RatinggroupController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['ratinggroup_name']=array('like',"%".G::getGpc('ratinggroup_name')."%");
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function bForbid_(){
		$nId=intval(G::getGpc('id','G'));

		if($this->is_system_ratinggroup($nId)){
			$this->E(Dyhb::L('系统等级分组无法禁用','Controller/Ratinggroup'));
		}
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			if($this->is_system_ratinggroup($nId)){
				$this->E(Dyhb::L('系统等级分组无法删除','Controller/Ratinggroup'));
			}
		}
	}

	public function is_system_ratinggroup($nId){
		$nId=intval($nId);

		if($nId<=5){
			return true;
		}

		return false;
	}

}
