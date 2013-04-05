<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加帖子入库控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AddtopicController extends Controller{

	public function index(){
		// 处理checkbox
		$arrCheckbox=array(
			'grouptopic_usesign','grouptopic_isanonymous','grouptopic_hiddenreplies',
			'grouptopic_ordertype','grouptopic_allownoticeauthor','grouptopic_iscomment',
			'grouptopic_sticktopic','grouptopic_addtodigest','grouptopic_isrecommend',
		);

		foreach($arrCheckbox as $sCheckbox){
			if(!isset($_POST[$sCheckbox])){
				$_POST[$sCheckbox]=0;
			}
		}
		
		if(!isset($_POST[''])){
			$_POST['grouptopic_ordertype']=0;
		}

		if(!isset($_POST[''])){
			$_POST['grouptopic_allownoticeauthor']=0;
		}
	
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

		$this->A(array('url'=>$sUrl),Dyhb::L('发布帖子成功','Controller/Grouptopic'),1);
	}

}
