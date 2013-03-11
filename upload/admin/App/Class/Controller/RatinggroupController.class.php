<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   等级分组控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class RatinggroupController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller/Common'));
		}
	}
	
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

		$oRatinggroup=RatinggroupModel::F('ratinggroup_id=?',$nId)->getOne();
		if(empty($oRatinggroup['ratinggroup_id'])){
			return false;
		}

		if($oRatinggroup['ratinggroup_issystem']==1){
			return true;
		}

		return false;
	}

}
