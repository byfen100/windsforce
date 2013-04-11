<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子高亮设置对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ColortopicdialogController extends Controller{

	public function index(){
		$nGroupid=intval(G::getGpc('groupid','G'));
		$arrGrouptopics=G::getGpc('dataids','G');

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
		
		$sGrouptopics=implode(',',$arrGrouptopics);

		// 读取帖子颜色
		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$arrGrouptopics[0])->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('帖子不存在','Controller/Grouptopicadmin'));
		}

		$arrColor=array('',array(1=>'0',2=>'0',3=>'0'),'',);
		if($oGrouptopic->grouptopic_color){
			$arrColorTemp=@unserialize($oGrouptopic->grouptopic_color);
			if($arrColorTemp){
				$arrColor=$arrColorTemp;
			}
		}

		$this->assign('arrColor',$arrColor);
		$this->assign('nGroupid',$nGroupid);
		$this->assign('sGrouptopics',$sGrouptopics);
		$this->assign('sTitle',Dyhb::L('你选择了 %d 篇帖子','Controller/Grouptopicadmin',null,1));
		
		$this->display('grouptopicadmin+colortopicdialog');
	}

}
