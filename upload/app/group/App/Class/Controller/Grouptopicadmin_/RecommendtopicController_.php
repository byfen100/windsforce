<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   推荐或者取消推荐帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class RecommendtopicController extends Controller{

	public function index(){
		$sGrouptopics=trim(G::getGpc('grouptopics'));
		$nGroupid=intval(G::getGpc('groupid'));
		$nStatus=intval(G::getGpc('status'));
		
		if(!Group_Extend::checkTopicadminRbac($nGroupid,array('group@grouptopicadmin@recommendtopic'))){
			$this->E(Dyhb::L('你没有推荐或者取消推荐帖子的权限','Controller/Grouptopicadmin'));
		}

		$arrGrouptopics=explode(',',$sGrouptopics);

		if(is_array($arrGrouptopics)){
			foreach($arrGrouptopics as $nGrouptopic){
				$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopic)->getOne();

				if(!empty($oGrouptopic['grouptopic_id'])){
					$oGrouptopic->grouptopic_isrecommend=$nStatus;
					$oGrouptopic->setAutofill(false);
					$oGrouptopic->save(0,'update');
					
					if($oGrouptopic->isError()){
						$this->E($oGrouptopic->getErrorMessage());
					}
				}
			}
		}

		$this->A(array('group_id'=>$nGroupid),$nStatus==1?Dyhb::L('推荐主题成功','Controller/Grouptopicadmin'):Dyhb::L('取消推荐主题成功','Controller/Grouptopicadmin'));
	}

}
