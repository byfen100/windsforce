<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   置顶或者取消置顶帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SticktopicController extends Controller{

	public function index(){
		$sGrouptopics=trim(G::getGpc('grouptopics'));
		$nGroupid=intval(G::getGpc('group_id'));
		$nStatus=intval(G::getGpc('status'));

		if(empty($nGroupid)){
			$this->E(Dyhb::L('没有待操作的小组','Controller/Grouptopicadmin'));
		}

		$oGroup=GroupModel::F('group_id=?',$nGroupid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('没有找到指定的小组','Controller/Grouptopicadmin'));
		}

		if(!Group_Extend::checkTopicadminRbac($oGroup,array('group@grouptopicadmin@sticktopic'))){
			$this->E(Dyhb::L('你没有置顶或者取消置顶帖子的权限','Controller/Grouptopicadmin'));
		}

		$arrGrouptopics=explode(',',$sGrouptopics);

		if(is_array($arrGrouptopics)){
			foreach($arrGrouptopics as $nGrouptopic){
				$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopic)->getOne();

				if(!empty($oGrouptopic['grouptopic_id'])){
					$oGrouptopic->grouptopic_sticktopic=$nStatus;
					$oGrouptopic->save(0,'update');
					
					if($oGrouptopic->isError()){
						$this->E($oGrouptopic->getErrorMessage());
					}
				}
			}
		}

		$this->A(array('group_id'=>$nGroupid),$nStatus==0?Dyhb::L('取消置顶主题成功','Controller/Grouptopicadmin'):Dyhb::L('置顶主题成功','Controller/Grouptopicadmin'));
	}

}
