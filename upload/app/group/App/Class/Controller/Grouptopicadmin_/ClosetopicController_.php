<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   关闭或者打开帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ClosetopicController extends Controller{

	public function index(){
		$sGrouptopics=trim(G::getGpc('grouptopics'));
		$nGroupid=intval(G::getGpc('groupid'));
		$nStatus=intval(G::getGpc('status'));

		if(!Groupadmin_Extend::checkTopicadminRbac($nGroupid,array('group@grouptopicadmin@closetopic'))){
			$this->E(Dyhb::L('你没有关闭或者打开帖子的权限','Controller/Grouptopicadmin'));
		}

		$arrGrouptopics=explode(',',$sGrouptopics);

		if(is_array($arrGrouptopics)){
			foreach($arrGrouptopics as $nGrouptopic){
				$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopic)->getOne();

				if(!empty($oGrouptopic['grouptopic_id'])){
					$oGrouptopic->grouptopic_isclose=$nStatus;
					$oGrouptopic->setAutofill(false);
					$oGrouptopic->save(0,'update');
					
					if($oGrouptopic->isError()){
						$this->E($oGrouptopic->getErrorMessage());
					}
				}
			}
		}

		$this->A(array('group_id'=>$nGroupid),$nStatus==0?Dyhb::L('打开主题成功','Controller/Grouptopicadmin'):Dyhb::L('关闭主题成功','Controller/Grouptopicadmin'));
	}

}
