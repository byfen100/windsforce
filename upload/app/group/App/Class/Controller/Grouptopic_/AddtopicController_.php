<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加帖子入库控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AddtopicController extends Controller{

	public function index(){
		try{
			Core_Extend::checkSpam();
		}catch(Exception $e){
			$this->E($e->getMessage());
		}
		
		// 处理checkbox
		$arrCheckbox=array(
			'grouptopic_usesign','grouptopic_isanonymous','grouptopic_hiddenreplies',
			'grouptopic_ordertype','grouptopic_allownoticeauthor','grouptopic_iscomment',
			'grouptopic_sticktopic','grouptopic_addtodigest','grouptopic_isrecommend',
			'grouptopic_onlycommentview',
		);

		foreach($arrCheckbox as $sCheckbox){
			if(!isset($_POST[$sCheckbox])){
				$_POST[$sCheckbox]=0;
			}
		}

		$_POST['grouptopic_content']=trim($_POST['grouptopic_content'],'<br />');

		// 小组相关检查
		$nGroupid=intval(G::getGpc('group_id','P'));

		// 访问权限
		$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$nGroupid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或者还在审核中','Controller/Grouptopic'));
		}

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGroup,true);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}
	
		// 保存帖子
		$oGrouptopic=new GrouptopicModel();

		if($oGroup->group_audittopic==1){
			$oGrouptopic->grouptopic_isaudit='0';
		}

		$oGrouptopic->save(0);

		if($oGrouptopic->isError()){
			$this->E($oGrouptopic->getErrorMessage());
		}

		// 更新积分
		Core_Extend::updateCreditByAction('group_addtopic',$GLOBALS['___login___']['user_id']);

		if($oGrouptopic['grouptopic_addtodigest']>0){
			Core_Extend::updateCreditByAction('group_topicdigest'.$oGrouptopic['grouptopic_addtodigest'],$GLOBALS['___login___']['user_id']);
		}

		if($oGrouptopic['grouptopic_sticktopic']>0){
			Core_Extend::updateCreditByAction('group_topicstick'.$oGrouptopic['grouptopic_sticktopic'],$GLOBALS['___login___']['user_id']);
		}

		if($oGrouptopic['grouptopic_isrecommend']>0){
			Core_Extend::updateCreditByAction('group_trecommend'.$oGrouptopic['grouptopic_isrecommend'],$GLOBALS['___login___']['user_id']);
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
