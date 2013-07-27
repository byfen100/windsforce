<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   热门帖子Api控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HottopicController extends Controller{

	public function index(){
		// 获取参数
		$nNum=intval(G::getGpc('num','G'));
		$nCutNum=intval(G::getGpc('cnum','G'));
		$nDate=intval(G::getGpc('date','G'));
		$sType=strtolower(trim(G::getGpc('type','G')));

		// 基本处理
		if($nNum<1){
			$nNum=1;
		}

		if($nNum>100){
			$nNum=100;
		}

		if(empty($nCutNum)){
			$nCutNum=20;
		}

		if($nDate<3600){
			$nData=3600;
		}

		// 获取帖子
		$arrGrouptopics=GrouptopicModel::F('create_dateline>? AND grouptopic_status=1 AND grouptopic_isaudit=1',CURRENT_TIMESTAMP-$nDate)->order('grouptopic_comments DESC')->limit(0,$nNum)->getAll();
		
		Core_Extend::api($arrGrouptopics,$sType);

		$this->assign('arrGrouptopics',$arrGrouptopics);
		$this->assign('nCutNum',$nCutNum);

		$this->display('api+hottopic');
	}

}
