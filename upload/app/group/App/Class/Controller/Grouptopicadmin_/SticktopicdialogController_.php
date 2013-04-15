<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   置顶或者取消置顶帖子对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SticktopicdialogController extends Controller{

	public function index(){
		$nGroupid=intval(G::getGpc('groupid','G'));
		$sGrouptopicid=trim(G::getGpc('grouptopicid','G'));
		$nStatus=intval(G::getGpc('status','G'));

		if(empty($nGroupid)){
			$this->E(Dyhb::L('没有待操作的小组','Controller/Grouptopicadmin'));
		}

		$oGroup=GroupModel::F('group_id=?',$nGroupid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('没有找到指定的小组','Controller/Grouptopicadmin'));
		}
		
		$arrGrouptopicid=Dyhb::normalize($sGrouptopicid);
		$sGrouptopics=implode(',',$arrGrouptopicid);
		
		if(empty($sGrouptopicid)){
			$this->E(Dyhb::L('没有待操作的帖子','Controller/Grouptopicadmin'));
		}

		if(isset($_GET['status'])){
			if($nStatus==3 && !Core_Extend::isAdmin()){
				$this->E(Dyhb::L('帖子已经全局置顶，你没有权限修改','Controller/Grouptopicadmin'));
			}
		}

		if(isset($_GET['status'])){
			$this->assign('nStatus',$nStatus);
		}

		$this->assign('sGrouptopics',$sGrouptopics);
		$this->assign('nGroupid',$nGroupid);
		$this->assign('sTitle',Dyhb::L('你选择了 %d 篇帖子','Controller/Grouptopicadmin',null,count($arrGrouptopicid)));
		
		$this->display('grouptopicadmin+sticktopicdialog');
	}

}
