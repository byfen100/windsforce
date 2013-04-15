<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   置顶或者取消置顶多个回帖控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class StickreplycommentController extends Controller{

	public function index(){
		$nGrouptopics=intval(G::getGpc('grouptopicid','G'));
		$sGrouptopiccomments=trim(G::getGpc('grouptopiccomments'));
		$nGroupid=intval(G::getGpc('group_id'));
		$nStatus=intval(G::getGpc('status'));
	
		if(empty($nGroupid)){
			$this->E(Dyhb::L('没有待操作的小组','Controller/Grouptopicadmin'));
		}

		$oGroup=GroupModel::F('group_id=?',$nGroupid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('没有找到指定的小组','Controller/Grouptopicadmin'));
		}

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopics)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你回复的主题不存在','Controller/Grouptopicadmin'));
		}

		if(!Group_Extend::checkCommentadminRbac($oGrouptopic->group,array('group@grouptopicadmin@stickreplycomment'))){
			$this->E(Dyhb::L('你没有权限置顶或者取消置顶回帖','Controller/Grouptopicadmin'));
		}
		
		$arrGrouptopiccomments=explode(',',$sGrouptopiccomments);

		if(is_array($arrGrouptopiccomments)){
			foreach($arrGrouptopiccomments as $nGrouptopiccomment){
				$oGrouptopiccomment=GrouptopiccommentModel::F('grouptopiccomment_id=?',$nGrouptopiccomment)->getOne();

				if(!empty($oGrouptopiccomment['grouptopiccomment_id'])){
					$oGrouptopiccomment->grouptopiccomment_stickreply=$nStatus;
					$oGrouptopiccomment->setAutofill(false);
					$oGrouptopiccomment->save(0,'update');
					
					if($oGrouptopiccomment->isError()){
						$this->E($oGrouptopiccomment->getErrorMessage());
					}
				}
			}
			
		}

		$sGrouptopicurl=Dyhb::U('group://topic@?id='.$oGrouptopic['grouptopic_id']);

		$this->A(array('group_id'=>$nGroupid,'grouptopic_url'=>$sGrouptopicurl),$nStatus==1?Dyhb::L('置顶回帖成功','Controller/Grouptopicadmin'):Dyhb::L('取消置顶回帖成功','Controller/Grouptopicadmin'));
	}

}
