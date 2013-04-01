<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子分类设置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CategorytopicController extends Controller{

	public function index(){
		$sGrouptopics=trim(G::getGpc('grouptopics'));
		$nGroupid=intval(G::getGpc('group_id'));
		$nCategoryid=intval(G::getGpc('category_id'));

		$arrGrouptopics=explode(',',$sGrouptopics);

		if(is_array($arrGrouptopics)){
			foreach($arrGrouptopics as $nGrouptopic){
				$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopic)->getOne();

				if(!empty($oGrouptopic['grouptopic_id'])){
					$oGrouptopic->grouptopiccategory_id=$nCategoryid;
					$oGrouptopic->save(0,'update');
					
					if($oGrouptopic->isError()){
						$this->E($oGrouptopic->getErrorMessage());
					}
				}
			}
		}

		$this->A(array('group_id'=>$nGroupid),'设置主题分类成功');
	}

}
