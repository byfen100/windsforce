<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   删除帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DeletetopicController extends Controller{

	public function index(){
		$sGrouptopics=trim(G::getGpc('grouptopics'));
		$nGroupid=intval(G::getGpc('groupid'));

		if(!Groupadmin_Extend::checkTopicadminRbac($oGroup,array('group@grouptopicadmin@deletetopic'))){
			$this->E(Dyhb::L('你没有删除帖子的权限','Controller/Grouptopicadmin'));
		}
		
		$arrGrouptopics=explode(',',$sGrouptopics);

		if(is_array($arrGrouptopics)){
			foreach($arrGrouptopics as $nGrouptopic){
				$oGrouptopicMeta=GrouptopicModel::M();
				$oGrouptopicMeta->deleteWhere(array('grouptopic_id'=>$nGrouptopic));
				
				if($oGrouptopicMeta->isError()){
					$this->E($oGrouptopicMeta->getErrorMessage());
				}

				// 删除主题关联的回帖
				$oGrouptopiccommentMeta=GrouptopiccommentModel::M();
				$oGrouptopiccommentMeta->deleteWhere(array('grouptopic_id'=>$nGrouptopic));
				
				if($oGrouptopiccommentMeta->isError()){
					$this->E($oGrouptopiccommentMeta->getErrorMessage());
				}
			}
		}

		$sGroupurl=Group_Extend::getGroupurl($oGroup);

		$this->A(array('group_id'=>$nGroupid,'group_url'=>$sGroupurl),Dyhb::L('删除主题成功','Controller/Grouptopicadmin'));
	}

}
