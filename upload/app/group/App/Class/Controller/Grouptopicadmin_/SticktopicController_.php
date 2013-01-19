<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   置顶或者取消置顶帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SticktopicController extends Controller{

	public function index(){
		$sGrouptopics=trim(G::getGpc('grouptopics'));
		$nGroupid=intval(G::getGpc('groupid'));
		$nStatus=intval(G::getGpc('status'));

		if(!Groupadmin_Extend::checkTopicadminRbac($nGroupid,array('group@grouptopicadmin@sticktopic'))){
			$this->E(Dyhb::L('你没有置顶或者取消置顶帖子的权限','Controller/Grouptopicadmin'));
		}

		$arrGrouptopics=explode(',',$sGrouptopics);

		$bAdmincredit=false;
		
		if(is_array($arrGrouptopics)){
			foreach($arrGrouptopics as $nGrouptopic){
				$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopic)->getOne();

				if(!empty($oGrouptopic['grouptopic_id'])){
					$bNeedcredit=false;
					if($oGrouptopic->grouptopic_sticktopic<$nStatus && $nStatus>0){
						$bNeedcredit=true;
					}
					
					$oGrouptopic->grouptopic_sticktopic=$nStatus;
					$oGrouptopic->setAutofill(false);
					$oGrouptopic->save(0,'update');
					
					if($oGrouptopic->isError()){
						$this->E($oGrouptopic->getErrorMessage());
					}

					if($bNeedcredit===true){
						Core_Extend::updateCreditByAction('group_topicstick'.$nStatus,$oGrouptopic['user_id']);
					}

					$bAdmincredit=true;
				}
			}
		}

		// 管理积分
		if($bAdmincredit===true){
			Core_Extend::updateCreditByAction('group_topicadmin',$GLOBALS['___login___']['user_id']);
		}

		$this->A(array('group_id'=>$nGroupid),$nStatus==0?Dyhb::L('取消置顶主题成功','Controller/Grouptopicadmin'):Dyhb::L('置顶主题成功','Controller/Grouptopicadmin'));
	}

}
