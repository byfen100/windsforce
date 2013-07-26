<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   删除我喜欢的话题($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class LovetopicdeleteController extends Controller{

	public function index(){
		$arrTopicid=G::getGpc('key');

		if($arrTopicid){
			foreach($arrTopicid as $nTopicid){
				$oGrouptopiclove=GrouptopicloveModel::F('grouptopic_id=? AND user_id=?',$nTopicid,$GLOBALS['___login___']['user_id'])->getOne();

				if(!empty($oGrouptopiclove['user_id'])){
					$oGrouptopiclove->destroy();

					// 整理帖子喜欢数
					$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$oGrouptopiclove['grouptopic_id'])->getOne();
					if(empty($oGrouptopic['grouptopic_id'])){
						$oGrouptopic->rebuildGrouptopicloves();

						if($oGrouptopic->isError()){
							$this->E($oGrouptopic->getErrorMessage());
						}
					}
				}
			}
		}else{
			$this->E(Dyhb::L('你没有选择待删除的帖子','Controller'));
		}

		$this->S(Dyhb::L('删除喜欢帖子成功','Controller'));
	}

}
