<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   删除单个帖子控制器($)*/

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
			}
		}

		$this->S('删除主题成功');
	}

}
