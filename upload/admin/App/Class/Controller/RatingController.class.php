<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   级别控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class RatingController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller/Common'));
		}
	}
	
	public function filter_(&$arrMap){
		$arrMap['rating_name']=array('like',"%".G::getGpc('rating_name')."%");
		
		$nRatinggroupId=G::getGpc('ratinggroup_id');
		if($nRatinggroupId!==null){
			$arrMap['ratinggroup_id']=$nRatinggroupId;
		}
	}

	public function bIndex_(){
		$sSort=trim(G::getGpc('sort_','G'));
		$this->getRatinggroup();

		$arrOptionData=$GLOBALS['_option_'];
		$this->assign('arrOptions',$arrOptionData);

		$arrRatingtype=G::listDir(WINDSFORCE_PATH.'/Public/images/rating');
		$this->assign('arrRatingtype',$arrRatingtype);
		
		if(!$sSort){
			$this->U('rating/index?sort_=asc');
		}
	}

	public function update_option(){
		$oOptionController=new OptionController();

		$oOptionController->update_option();
	}
	
	public function bEdit_(){
		$this->getRatinggroup();
	}
	
	public function getRatinggroup(){
		$arrRatinggroup=array_merge(array(array('ratinggroup_id'=>0,'ratinggroup_title'=>Dyhb::L('未分组','Controller/Rating'))),
				RatinggroupModel::F()->setColumns('ratinggroup_id,ratinggroup_title')->asArray()->all()->query()
		);
		$this->assign('arrRatinggroup',$arrRatinggroup);
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				if($this->is_system_rating($nId)){
					$this->E(Dyhb::L('系统级别无法删除','Controller/Rating'));
				}
			}
		}
	}

	public function change_ratinggroup(){
		$sId=trim(G::getGpc('id','G'));
		$nRatinggroupId=intval(G::getGpc('ratinggroup_id','G'));
		
		if(!empty($sId)){
			if($nRatinggroupId){
				// 判断级别分组是否存在
				$oRatinggroup=RatinggroupModel::F('ratinggroup_id=?',$nRatinggroupId)->getOne();
				if(empty($oRatinggroup['ratinggroup_id'])){
					$this->E(Dyhb::L('你要移动的级别分组不存在','Controller/Rating'));
				}
			}
			
			$arrIds=explode(',', $sId);
			foreach($arrIds as $nId){
				if($this->is_system_rating($nId)){
					$this->E(Dyhb::L('系统级别无法移动','Controller/Rating'));
				}
				
				$oRating=RatingModel::F('rating_id=?',$nId)->getOne();
				$oRating->ratinggroup_id=$nRatinggroupId;
				$oRating->save(0,'update');
				
				if($oRating->isError()){
					$this->E($oRating->getErrorMessage());
				}
			}

			$this->S(Dyhb::L('移动级别分组成功','Controller/Rating'));
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function is_system_rating($nId){
		$nId=intval($nId);

		if($nId<=100){
			return true;
		}

		return false;
	}

}
