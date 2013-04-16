<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子分类设置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CategorytopicController extends Controller{

	public function index(){
		$sGrouptopics=trim(G::getGpc('grouptopics'));
		$nGroupid=intval(G::getGpc('group_id'));
		$nCategoryid=intval(G::getGpc('category_id'));

		if(empty($nGroupid)){
			$this->E(Dyhb::L('没有待操作的小组','Controller/Grouptopicadmin'));
		}

		$oGroup=GroupModel::F('group_id=?',$nGroupid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('没有找到指定的小组','Controller/Grouptopicadmin'));
		}

		if(!Groupadmin_Extend::checkTopicadminRbac($oGroup,array('group@grouptopicadmin@categorytopic'))){
			$this->E(Dyhb::L('你没有帖子分类设置的权限','Controller/Grouptopicadmin'));
		}

		$arrGrouptopics=explode(',',$sGrouptopics);

		if(is_array($arrGrouptopics)){
			foreach($arrGrouptopics as $nGrouptopic){
				$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopic)->getOne();

				if(!empty($oGrouptopic['grouptopic_id'])){
					$oGrouptopic->grouptopiccategory_id=$nCategoryid;
					$oGrouptopic->setAutofill(false);
					$oGrouptopic->save(0,'update');
					
					if($oGrouptopic->isError()){
						$this->E($oGrouptopic->getErrorMessage());
					}
				}
			}
		}

		$this->A(array('group_id'=>$nGroupid),Dyhb::L('设置主题分类成功','Controller/Grouptopicadmin'));
	}

}
