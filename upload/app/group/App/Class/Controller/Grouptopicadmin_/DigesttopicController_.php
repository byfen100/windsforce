<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   精华或者取消精华帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DigesttopicController extends Controller{

	public function index(){
		$sGrouptopics=trim(G::getGpc('grouptopics'));
		$nGroupid=intval(G::getGpc('group_id'));
		$nStatus=intval(G::getGpc('status'));

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
