<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   推荐或者取消推荐帖子对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class RecommendtopicdialogController extends Controller{

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
			if($nStatus==2 && !Core_Extend::isAdmin()){
				$this->E(Dyhb::L('帖子已经由系统推荐，你没有权限修改','Controller'));
			}
		}

		if(isset($_GET['status'])){
			$this->assign('nStatus',$nStatus);
		}
		
		$this->assign('sGrouptopics',$sGrouptopics);
		$this->assign('nGroupid',$nGroupid);
		$this->assign('sTitle',Dyhb::L('你选择了 %d 篇帖子','Controller',null,count($arrGrouptopicid)));
		
		$this->display('grouptopicadmin+recommendtopicdialog');
	}

}
