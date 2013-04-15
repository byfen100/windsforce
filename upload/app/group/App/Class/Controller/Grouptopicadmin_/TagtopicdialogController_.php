<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子标签对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TagtopicdialogController extends Controller{

	public function index(){
		$nGroupid=intval(G::getGpc('groupid','G'));
		$nGrouptopicid=intval(G::getGpc('grouptopicid','G'));

		if(empty($nGroupid)){
			$this->E(Dyhb::L('没有待操作的小组','Controller/Grouptopicadmin'));
		}

		$oGroup=GroupModel::F('group_id=?',$nGroupid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('没有找到指定的小组','Controller/Grouptopicadmin'));
		}
		
		if(empty($nGrouptopicid)){
			$this->E(Dyhb::L('没有待操作的帖子','Controller/Grouptopicadmin'));
		}

		// 获取帖子标签
		$sTag='';

		$arrTags=GrouptopictagindexModel::F('grouptopic_id=?',$nGrouptopicid)->getAll();
		if(is_array($arrTags)){
			$arrTemptag=array();
			foreach($arrTags as $oTag){
				$arrTemptag[]=$oTag['grouptopictag_id'];
			}

			// 取得标签
			$arrWhere['grouptopictag_id']=array('in',$arrTemptag);
			$arrGrouptopictags=GrouptopictagModel::F($arrWhere)->all()->get();
			if(is_array($arrGrouptopictags)){
				foreach($arrGrouptopictags as $oGrouptopictag){
					$sTag.=','.$oGrouptopictag['grouptopictag_name'];
				}
			}

			$sTag=trim($sTag,',');
		}

		$this->assign('nGroupid',$nGroupid);
		$this->assign('sGrouptopics',$nGrouptopicid);
		$this->assign('sTag',$sTag);
		$this->assign('sTitle',Dyhb::L('你选择了 %d 篇帖子','Controller/Grouptopicadmin',null,1));
		
		$this->display('grouptopicadmin+tagtopicdialog');
	}

}
