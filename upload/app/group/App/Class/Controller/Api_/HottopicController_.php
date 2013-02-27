<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   热门帖子Api控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HottopicController extends Controller{

	public function index(){
		$nNum=intval(G::getGpc('num','G'));
		$nCutNum=intval(G::getGpc('cnum','G'));
		$nDate=intval(G::getGpc('date','G'));

		if($nNum<1){
			$this->E('热门帖子调用数量不能为空');
		}

		if(empty($nCutNum)){
			$nCutNum=20;
		}

		if($nDate<3600){
			$nData=3600;
		}

		$arrGrouptopics=GrouptopicModel::F('create_dateline>?',CURRENT_TIMESTAMP-$nDate)->order('grouptopic_comments DESC')->limit(0,$nNum)->getAll();
		$this->assign('arrGrouptopics',$arrGrouptopics);
		$this->assign('nCutNum',$nCutNum);

		$this->display('api+hottopic');
	}
}
