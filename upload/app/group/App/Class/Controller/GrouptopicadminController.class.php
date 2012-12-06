<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   群组操作控制器($)*/

!defined('DYHB_PATH') && exit;

class GrouptopicadminController extends InitController{

	public function deletetopic_dialog(){
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

	public function deletetopic(){
		$nGroupid=intval(G::getGpc('group_id','P'));
		$sGrouptopics=G::getGpc('grouptopics','G');

		$arrGrouptopics=implode(',',$sGrouptopics);
		if(is_array($arrGrouptopics)){
			foreach($arrGrouptopics as $nGrouptopic){
				$oGrouptopicMeta=GrouptopicModel::M();
				$oGrouptopicMeta->deleteWhere(array('grouptopic_id'=>$nGrouptopic));
			}
		}

		$this->S('删除主题成功');
	}

}
