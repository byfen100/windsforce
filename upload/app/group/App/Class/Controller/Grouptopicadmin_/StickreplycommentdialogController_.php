<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   置顶或者取消置顶多个回帖对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class StickreplycommentdialogController extends Controller{

	public function index(){
		$nGroupid=intval(G::getGpc('groupid','G'));
		$arrGrouptopics=G::getGpc('dataids','G');
		$sGrouptopiccomments=G::getGpc('commentids','G');

		if(empty($nGroupid)){
			$this->E(Dyhb::L('没有待操作的小组','Controller/Grouptopicadmin'));
		}

		$oGroup=GroupModel::F('group_id=?',$nGroupid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('没有找到指定的小组','Controller/Grouptopicadmin'));
		}
		
		if(empty($arrGrouptopics)){
			$this->E(Dyhb::L('没有待操作的帖子','Controller/Grouptopicadmin'));
		}

		$arrGrouptopiccomments=Dyhb::normalize($sGrouptopiccomments);

		if(empty($arrGrouptopiccomments)){
			$this->E(Dyhb::L('没有待操作的回帖','Controller/Grouptopicadmin'));
		}

		$sGrouptopics=implode(',',$arrGrouptopics);

		$this->assign('sGrouptopics',$sGrouptopics);
		$this->assign('nGroupid',$nGroupid);
		$this->assign('sGrouptopiccomments',implode(',',$arrGrouptopiccomments));
		$this->assign('sTitle',Dyhb::L('你选择了 %d 篇帖子','Controller/Grouptopicadmin',null,count($arrGrouptopiccomments)));
		
		$this->display('grouptopicadmin+stickreplycommentdialog');
	}

}
