<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   添加帖子入库控制器($)*/

!defined('DYHB_PATH') && exit;

class AddtopicController extends Controller{

	public function index(){
		$oGrouptopic=new GrouptopicModel();
		$oGrouptopic->save(0);

		if($oGrouptopic->isError()){
			$this->E($oGrouptopic->getErrorMessage());
		}

		$nTopicnum=GrouptopicModel::F('group_id=?',$oGrouptopic->group_id)->getCounts();
		$nCommnum=GrouptopicModel::F('group_id=?',$oGrouptopic->group_id)->getSum('grouptopic_comments');
		$oGroup=GroupModel::F('group_id=?',$oGrouptopic->group_id)->getOne();
		$oGroup->group_topicnum=$nTopicnum;
		$oGroup->group_topiccomment=$nCommnum;

		$arrLatestData=array('topictime'=>$oGrouptopic->create_dateline,'topicid'=>$oGrouptopic->grouptopic_id,'topicuserid'=>$GLOBALS['___login___']['user_id']);
		
		$arrLatestData['topictitle']=$oGrouptopic['grouptopic_title'];
		$oGroup->group_latestcomment=serialize($arrLatestData);

		$oGroup->save(0,'update');

		if($oGroup->isError()){
			$this->E($oGroup->getErrorMessage());
		}


		$sUrl=Dyhb::U('group://topic@?id='.$oGrouptopic['grouptopic_id']);

		$this->A(array('url'=>$sUrl),'发布帖子成功',1);
	}

}
