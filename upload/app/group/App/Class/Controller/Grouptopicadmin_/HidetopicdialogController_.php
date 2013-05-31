<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   隐藏或者显示帖子对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HidetopicdialogController extends Controller{

	public function index(){
		$nGroupid=intval(G::getGpc('groupid','G'));
		$sGrouptopicid=trim(G::getGpc('grouptopicid','G'));
		$nStatus=intval(G::getGpc('status','G'));
		
		$arrGrouptopicid=Dyhb::normalize($sGrouptopicid);
		$sGrouptopics=implode(',',$arrGrouptopicid);

		if(empty($sGrouptopics)){
			$this->E(Dyhb::L('没有待操作的帖子','Controller'));
		}

		if(isset($_GET['status'])){
			$this->assign('nStatus',$nStatus);
		}
		
		$this->assign('sGrouptopics',$sGrouptopics);
		$this->assign('nGroupid',$nGroupid);
		$this->assign('sTitle',Dyhb::L('你选择了 %d 篇帖子','Controller',null,count($arrGrouptopicid)));
		
		$this->display('grouptopicadmin+hidetopicdialog');
	}

}
