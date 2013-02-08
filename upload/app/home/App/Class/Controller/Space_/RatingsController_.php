<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   等级规则($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class RatingsController extends Controller{

	public function index(){
		Core_Extend::loadCache('rating');
		Core_Extend::loadCache('ratinggroup');

		// 等级分组
		$nCId=intval(G::getGpc('cid','G'));
		$arrRatinggroups=$GLOBALS['_cache_']['ratinggroup'];

		$arrRatinggroupIds=array();
		foreach($arrRatinggroups as $oRatinggroup){
			$arrRatinggroupIds[]=$oRatinggroup['ratinggroup_id'];
		}

		if(!empty($nCId) && in_array($nCId,$arrRatinggroupIds)){
			$arrRatings=array();
			foreach($GLOBALS['_cache_']['rating'] as $arrRating){
				if($arrRating['ratinggroup_id']==$nCId){
					$arrRatings[]=$arrRating;
				}
			}
		}else{
			$arrRatings=$GLOBALS['_cache_']['rating'];
		}

		$this->assign('nCId',$nCId);
		$this->assign('arrRatings',$arrRatings);
		$this->assign('arrRatinggroups',$arrRatinggroups);

		$this->display('space+ratings');
	}

	public $_oUserInfo=null;

	public function index_title_(){
		return $this->_oUserInfo['user_name'].' - '.Dyhb::L('积分','Controller/Space');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
