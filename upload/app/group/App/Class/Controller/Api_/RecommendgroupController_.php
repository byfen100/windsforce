<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   推荐小组Api控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class RecommendgroupController extends Controller{

	public function index(){
		$nNum=intval(G::getGpc('num','G'));
		$nCutNum=intval(G::getGpc('cnum','G'));

		if($nNum<1){
			$this->E('推荐小组调用数量不能为空');
		}

		$arrGroups=GroupModel::F('group_isrecommend=?',1)->order('create_dateline DESC')->limit(0,$nNum)->getAll();
		$this->assign('arrGroups',$arrGroups);
		$this->assign('nCutNum',$nCutNum);

		$this->display('api+recommendgroup');
	}
}
