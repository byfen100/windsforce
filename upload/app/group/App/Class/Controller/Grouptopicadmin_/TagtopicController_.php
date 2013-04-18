<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   设置帖子标签控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TagtopicController extends Controller{

	public function index(){
		$sGrouptopics=trim(G::getGpc('grouptopics'));
		$nGroupid=intval(G::getGpc('groupid'));
		$sTags=G::getGpc('tags');
		$sOldTags=G::getGpc('old_tags');
		
		if(!Groupadmin_Extend::checkTopicadminRbac($oGroup,array('group@grouptopicadmin@tagtopic'))){
			$this->E(Dyhb::L('你没有设置帖子标签的权限','Controller/Grouptopicadmin'));
		}

		$arrGrouptopics=explode(',',$sGrouptopics);

		if(is_array($arrGrouptopics)){
			foreach($arrGrouptopics as $nGrouptopic){
				$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopic)->getOne();

				if(!empty($oGrouptopic['grouptopic_id'])){
					// 保存帖子标签
					if($sTags){
						$oGrouptopictag=Dyhb::instance('GrouptopictagModel');
						$oGrouptopictag->addTag($nGrouptopic,$sTags,$sOldTags);

						if($oGrouptopictag->isError()){
							$this->E($oGrouptopictag->getErrorMessage());
						}
					}
				}
			}
		}

		$this->A(array('group_id'=>$nGroupid),Dyhb::L('主题标签更新成功','Controller/Grouptopicadmin'));
	}

}
