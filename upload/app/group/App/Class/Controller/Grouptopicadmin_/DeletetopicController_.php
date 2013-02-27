<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   删除单个帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DeletetopicController extends Controller{

	public function index(){
		$nGroupid=intval(G::getGpc('group_id','P'));
		$sGrouptopics=G::getGpc('grouptopics','G');

		$arrGrouptopics=implode(',',$sGrouptopics);
		if(is_array($arrGrouptopics)){
			foreach($arrGrouptopics as $nGrouptopic){
				$oGrouptopicMeta=GrouptopicModel::M();
				$oGrouptopicMeta->deleteWhere(array('grouptopic_id'=>$nGrouptopic));
				
				if($oGrouptopicMeta->isError()){
					$this->E($oGrouptopicMeta->getErrorMessage());
				}
			}
		}

		$this->S('删除主题成功');
	}

}
