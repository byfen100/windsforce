<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   推荐小组Api控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;
!defined('IN_API') && !defined('IN_APISELF') && exit;

class RecommendgroupController extends Controller{

	public function index(){
		// 获取参数
		$nNum=intval(G::getGpc('num','G'));
		$nCutNum=intval(G::getGpc('cnum','G'));
		$sType=strtolower(trim(G::getGpc('type','G')));

		// 基本处理
		if($nNum<1){
			$nNum=1;
		}

		if($nNum>30){
			$nNum=30;
		}

		if(empty($nCutNum)){
			$nCutNum=30;
		}

		// 获取小组
		$arrGroups=GroupModel::F('group_isrecommend=? AND group_status=1 AND group_isaudit=1',1)->order('create_dateline DESC')->limit(0,$nNum)->getAll();
		
		Core_Extend::api($arrGroups,$sType);
		
		$this->assign('arrGroups',$arrGroups);
		$this->assign('nCutNum',$nCutNum);

		$this->display('api+recommendgroup');
	}

}
