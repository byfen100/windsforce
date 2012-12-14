<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   删除单个帖子对话框控制器($)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		$nGroupid=intval(G::getGpc('groupid','G'));
		$arrGrouptopics=G::getGpc('dataids','G');
		
		if(empty($nGroupid)){
			$this->E('没有待操作的小组');
		}

		$oGroup=GroupModel::F('group_id=?',$nGroupid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E('没有找到指定的小组');
		}
		
		if(empty($arrGrouptopics)){
			$this->E('没有待操作的帖子');
		}

		$sGrouptopics=implode(',',$arrGrouptopics);

		$this->assign('nGroupid',$nGroupid);
		$this->assign('sGrouptopics',$sGrouptopics);
		
		$this->display('grouptopicadmin+deletetopicdialog');
	}

}
