<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   删除回帖控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DeletecommentController extends Controller{

	public function index(){
		$nGrouptopics=intval(G::getGpc('grouptopics'));
		$sGrouptopiccomments=trim(G::getGpc('grouptopiccomments'));
		$nGroupid=intval(G::getGpc('groupid'));

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopics)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你回复的主题不存在','Controller/Grouptopicadmin'));
		}

		if(!Groupadmin_Extend::checkCommentadminRbac($oGrouptopic->group,array('group@grouptopicadmin@deletecomment'))){
			$this->E(Dyhb::L('你没有权限删除回帖','Controller/Grouptopicadmin'));
		}

		$arrGrouptopiccomments=explode(',',$sGrouptopiccomments);

		if(is_array($arrGrouptopiccomments)){
			foreach($arrGrouptopiccomments as $nGrouptopiccomment){
				// 回帖回收站功能开启
				if($GLOBALS['_cache_']['group_option']['group_deletecomment_recyclebin']==1){
					$oGrouptopiccomment=GrouptopiccommentModel::F('grouptopiccomment_id=?',$nGrouptopiccomment)->getOne();

					if(!empty($oGrouptopiccomment['grouptopiccomment_id'])){
						$oGrouptopiccomment->grouptopiccomment_status='0';
						$oGrouptopiccomment->save(0,'update');

						if($oGrouptopiccomment->isError()){
							$this->E($oGrouptopiccomment->getErrorMessage());
						}
					}
				}else{
					$oGrouptopiccommentMeta=GrouptopiccommentModel::M();
					$oGrouptopiccommentMeta->deleteWhere(array('grouptopiccomment_id'=>$nGrouptopiccomment));
						
					if($oGrouptopiccommentMeta->isError()){
						$this->E($oGrouptopiccommentMeta->getErrorMessage());
					}
				}
			}

			if($GLOBALS['_cache_']['group_option']['group_deletecomment_recyclebin']=='0'){
				// 更新帖子评论数量
				$oGrouptopic->grouptopic_comments=GrouptopiccommentModel::F('grouptopic_id=?',$nGrouptopics)->all()->getCounts();
				$oGrouptopic->setAutofill(false);
				$oGrouptopic->save(0,'update');

				if($oGrouptopic->isError()){
					$this->E($oGrouptopic->getErrorMessage());
				}
			}
		}

		$sGrouptopicurl=Dyhb::U('group://topic@?id='.$oGrouptopic['grouptopic_id']);

		$this->A(array('group_id'=>$nGroupid,'grouptopic_url'=>$sGrouptopicurl),Dyhb::L('删除回帖成功','Controller/Grouptopicadmin'));
	}

}
