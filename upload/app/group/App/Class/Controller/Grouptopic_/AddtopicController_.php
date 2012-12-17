<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   添加帖子入库控制器($)*/

!defined('DYHB_PATH') && exit;

class AddtopicController extends Controller{

	public function index(){
		// 保存帖子
		$oGrouptopic=new GrouptopicModel();
		$oGrouptopic->save(0);

		if($oGrouptopic->isError()){
			$this->E($oGrouptopic->getErrorMessage());
		}

		// 保存帖子标签
		$sTags=trim(G::getGpc('tags','P'));
		if($sTags){
			$oGrouptopictag=Dyhb::instance('GrouptopictagModel');
			$oGrouptopictag->addTag($oGrouptopic->grouptopic_id,$sTags);

			if($oGrouptopictag->isError()){
				$this->E($oGrouptopictag->getErrorMessage());
			}
		}

		// 更新小组帖子数量和最后更新
		$nTopicnum=GrouptopicModel::F('group_id=?',$oGrouptopic->group_id)->getCounts();
		$oGroup=GroupModel::F('group_id=?',$oGrouptopic->group_id)->getOne();
		$oGroup->group_topicnum=$nTopicnum;

		$arrLatestData=array(
			'topictime'=>$oGrouptopic->create_dateline,
			'topicid'=>$oGrouptopic->grouptopic_id,
			'topicuserid'=>$GLOBALS['___login___']['user_id'],
			'topictitle'=>$oGrouptopic['grouptopic_title'],
		);
		$oGroup->group_latestcomment=serialize($arrLatestData);
		$oGroup->save(0,'update');

		if($oGroup->isError()){
			$this->E($oGroup->getErrorMessage());
		}

		// 跳转到帖子
		$sUrl=Dyhb::U('group://topic@?id='.$oGrouptopic['grouptopic_id']);
		$this->A(array('url'=>$sUrl),'发布帖子成功',1);
	}

}
