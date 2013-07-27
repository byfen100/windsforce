<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   审核多个回帖对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AuditcommentdialogController extends Controller{

	public function index(){
		$nGroupid=intval(G::getGpc('groupid','G'));
		$nGrouptopicid=intval(G::getGpc('grouptopicid','G'));
		$sGrouptopiccomments=G::getGpc('commentids','G');
		
		if(empty($nGrouptopicid)){
			$this->E(Dyhb::L('没有待操作的帖子','Controller'));
		}

		$arrGrouptopiccomments=Dyhb::normalize($sGrouptopiccomments);

		if(empty($arrGrouptopiccomments)){
			$this->E(Dyhb::L('没有待操作的回帖','Controller'));
		}

		$this->assign('sGrouptopics',$nGrouptopicid);
		$this->assign('nGroupid',$nGroupid);
		$this->assign('sGrouptopiccomments',implode(',',$arrGrouptopiccomments));
		$this->assign('sTitle',Dyhb::L('你选择了 %d 篇帖子','Controller',null,count($arrGrouptopiccomments)));
		
		$this->display('grouptopicadmin+auditcommentdialog');
	}

}
