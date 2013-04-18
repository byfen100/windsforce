<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   最新帖子Api控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class NewtopicController extends Controller{

	public function index(){
		// 获取参数
		$nNum=intval(G::getGpc('num','G'));
		$sType=strtolower(trim(G::getGpc('type','G')));
		$sType=strtolower(trim(G::getGpc('type','G')));

		// 基本处理
		if($nNum<1){
			$nNum=1;
		}

		// 获取帖子
		$arrGrouptopics=GrouptopicModel::F('grouptopic_status=? AND grouptopic_isaudit=1',1)->order('create_dateline DESC')->limit(0,$nNum)->getAll();

		Core_Extend::api($arrGrouptopics,$sType);

		$this->assign('arrGrouptopics',$arrGrouptopics);

		$this->display('api+newtopic');
	}

}
