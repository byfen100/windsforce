<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加帖子入库控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AddtopicController extends GlobalchildController{

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

		$arrParsemessage=Core_Extend::contentParsetag(trim($_POST['grouptopic_content']));
		$_POST['grouptopic_content']=$arrParsemessage['content'];

		// 小组相关检查
		$nGroupid=intval(G::getGpc('group_id','P'));

		// 访问权限
		$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$nGroupid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或者还在审核中','Controller'));
		}

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGroup,true);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		if($GLOBALS['_option_']['seccode_publish_status']==1){
			$this->_oParentcontroller->check_seccode(true);
		}
	
		// 保存帖子
		$oGrouptopic=new GrouptopicModel();
		$oGrouptopic->grouptopic_update=CURRENT_TIMESTAMP;
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
		$oGroup->group_topictodaynum=$oGroup->group_topictodaynum+1;
		$oGroup->group_totaltodaynum=$oGroup->group_topictodaynum+$oGroup->group_topiccommenttodaynum;

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

		// 保存小组今日数据
		GroupoptionModel::uploadOption('group_topictodaynum',$GLOBALS['_cache_']['group_option']['group_topictodaynum']+1);
		GroupoptionModel::uploadOption('group_totaltodaynum',$GLOBALS['_cache_']['group_option']['group_totaltodaynum']+1);

		$this->cache_site_();

		// 发送feed
		$sFeedtemplate='<div class="feed_addgrouptopic"><span class="feed_title">'.Dyhb::L('发布了一篇帖子','Controller').'&nbsp;<a href="{@grouptopic_link}">'.$oGrouptopic['grouptopic_title'].'</a></span><div class="feed_content">{grouptopic_message}</div><div class="feed_action"><a href="{@grouptopic_link}#comments">'.Dyhb::L('回复','Controller').'</a></div></div>';

		$arrFeeddata=array(
			'@grouptopic_link'=>'group://grouptopic/view?id='.$oGrouptopic['grouptopic_id'],
			'grouptopic_message'=>Core_Extend::subString($oGrouptopic['grouptopic_content'],100,false,1,false),
		);

		try{
			Core_Extend::addFeed($sFeedtemplate,$arrFeeddata);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		// 发送@提醒
		if($arrParsemessage['atuserids']){
			foreach($arrParsemessage['atuserids'] as $nAtuserid){
				if($nAtuserid!=$GLOBALS['___login___']['user_id']){
					$sGrouptopicmessage=Core_Extend::subString($oGrouptopic['grouptopic_content'],100,false,1,false);
					
					$sNoticetemplate='<div class="notice_atgrouptopic"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('在主题中提到了你','Controller').'</span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{content_message}</span></div></div><div class="notice_action"><a href="{@grouptopic_link}">'.Dyhb::L('查看','Controller').'</a></div></div>';

					$arrNoticedata=array(
						'@space_link'=>'group://space@?id='.$GLOBALS['___login___']['user_id'],
						'user_name'=>$GLOBALS['___login___']['user_name'],
						'@grouptopic_link'=>'group://grouptopic/view?id='.$oGrouptopic['grouptopic_id'],
						'content_message'=>$sGrouptopicmessage,
					);

					try{
						Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$nAtuserid,'atgrouptopic',$oGrouptopic['grouptopic_id']);
					}catch(Exception $e){
						$this->E($e->getMessage());
					}
				}
			}
		}

		// 跳转到帖子
		$sUrl=Dyhb::U('group://topic@?id='.$oGrouptopic['grouptopic_id']);

		$this->A(array('url'=>$sUrl),Dyhb::L('发布帖子成功','Controller'),1);
	}

	protected function cache_site_(){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache("group_site");
	}

}
