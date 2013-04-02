<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   置顶或者取消置顶帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TagtopicController extends Controller{

	public function index(){
		$sGrouptopics=trim(G::getGpc('grouptopics'));
		$nGroupid=intval(G::getGpc('group_id'));
		$sTags=G::getGpc('tags');
		$sOldTags=G::getGpc('old_tags');

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
