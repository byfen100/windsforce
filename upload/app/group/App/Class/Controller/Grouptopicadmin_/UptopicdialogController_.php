<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子下沉和提升对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UptopicdialogController extends Controller{

	public function index(){
		$nGroupid=intval(G::getGpc('groupid','G'));
		$sGrouptopicid=trim(G::getGpc('grouptopicid','G'));
		
		$arrGrouptopicid=Dyhb::normalize($sGrouptopicid);
		$sGrouptopics=implode(',',$arrGrouptopicid);
		
		if(empty($sGrouptopics)){
			$this->E(Dyhb::L('没有待操作的帖子','Controller'));
		}
	
		$this->assign('nGroupid',$nGroupid);
		$this->assign('sGrouptopics',$sGrouptopics);
		$this->assign('sTitle',Dyhb::L('你选择了 %d 篇帖子','Controller',null,count($arrGrouptopicid)));
		
		$this->display('grouptopicadmin+uptopicdialog');
	}

}
