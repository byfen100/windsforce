<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   精华或者取消精华帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DigesttopicController extends Controller{

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

		if(!Group_Extend::checkTopicadminRbac($oGroup,array('group@grouptopicadmin@digesttopic'))){
			$this->E(Dyhb::L('你没有精华或者取消精华帖子的权限','Controller/Grouptopicadmin'));
		}
		
		$arrGrouptopics=explode(',',$sGrouptopics);

		if(is_array($arrGrouptopics)){
			foreach($arrGrouptopics as $nGrouptopic){
				$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopic)->getOne();

				if(!empty($oGrouptopic['grouptopic_id'])){
					$oGrouptopic->grouptopic_addtodigest=$nStatus;
					$oGrouptopic->save(0,'update');
					
					if($oGrouptopic->isError()){
						$this->E($oGrouptopic->getErrorMessage());
					}
				}
			}
		}

		$this->A(array('group_id'=>$nGroupid),$nStatus==0?Dyhb::L('取消主题精华成功','Controller/Grouptopicadmin'):Dyhb::L('主题设置精华成功','Controller/Grouptopicadmin'));
	}

}
