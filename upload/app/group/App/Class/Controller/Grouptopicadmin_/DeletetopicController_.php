<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   删除帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DeletetopicController extends Controller{

	public function index(){
		$sGrouptopics=trim(G::getGpc('grouptopics'));
		$nGroupid=intval(G::getGpc('groupid'));

		if(!Groupadmin_Extend::checkTopicadminRbac($nGroupid,array('group@grouptopicadmin@deletetopic'))){
			$this->E(Dyhb::L('你没有删除帖子的权限','Controller/Grouptopicadmin'));
		}
		
		$arrGrouptopics=explode(',',$sGrouptopics);

		$bAdmincredit=false;
		
		if(is_array($arrGrouptopics)){
			foreach($arrGrouptopics as $nGrouptopic){
				$oGrouptopic=GrouptopicModel::F('grouptopic_id=? AND grouptopic_status=1',$nGrouptopic)->getOne();

				if(!empty($oGrouptopic['grouptopic_id'])){
					$nUserid=$oGrouptopic['user_id'];

					// 帖子回收站功能开启
					if($GLOBALS['_cache_']['group_option']['group_deletetopic_recyclebin']==1){
						$oGrouptopic->grouptopic_status='0';
						$oGrouptopic->save(0,'update');

						if($oGrouptopic->isError()){
							$this->E($oGrouptopic->getErrorMessage());
						}
					}else{
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

					Core_Extend::updateCreditByAction('group_topicdelete',$nUserid);

					$bAdmincredit=true;
				}
			}
		}

		// 管理积分
		if($bAdmincredit===true){
			Core_Extend::updateCreditByAction('group_topicadmin',$GLOBALS['___login___']['user_id']);
		}

		$sGroupurl=Group_Extend::getGroupurl($nGroupid);
		$this->A(array('group_id'=>$nGroupid,'group_url'=>$sGroupurl),Dyhb::L('删除主题成功','Controller/Grouptopicadmin'));
	}

}
