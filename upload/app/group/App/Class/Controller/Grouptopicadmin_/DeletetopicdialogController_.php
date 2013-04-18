<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   删除帖子对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DeletetopicdialogController extends Controller{

	public function index(){
		$nGroupid=intval(G::getGpc('groupid','G'));
		$sGrouptopicid=trim(G::getGpc('grouptopicid','G'));

		$arrGrouptopicid=Dyhb::normalize($sGrouptopicid);
		$sGrouptopics=implode(',',$arrGrouptopicid);

		if(empty($sGrouptopicid)){
			$this->E(Dyhb::L('没有待操作的帖子','Controller/Grouptopicadmin'));
		}

		$this->assign('sGrouptopics',$sGrouptopics);
		$this->assign('nGroupid',$nGroupid);
		$this->assign('sTitle',Dyhb::L('你选择了 %d 篇帖子','Controller/Grouptopicadmin',null,count($arrGrouptopicid)));
		
		$this->display('grouptopicadmin+deletetopicdialog');
	}

}
