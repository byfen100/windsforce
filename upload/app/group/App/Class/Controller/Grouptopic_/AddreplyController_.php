<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加帖子回复入库控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入通用评论检测相关函数 */
require_once(Core_Extend::includeFile('function/Comment_Extend'));

class AddreplyController extends GlobalchildController{

	public function index(){
		try{
			Core_Extend::checkSpam();
		}catch(Exception $e){
			$this->E($e->getMessage());
		}
		
		$nId=intval(G::getGpc('tid'));
		$nSimple=intval(G::getGpc('simple_comment'));

		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定回复主题的ID','Controller/Grouptopic'));
		}

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nId)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你回复的主题不存在','Controller/Grouptopic'));
		}

		$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$oGrouptopic->group_id)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('你回复的帖子所在小组不存在','Controller/Grouptopic'));
		}

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGroup,true);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		// 快捷回复兼容性
		if($nSimple==1){
			foreach(array('message','name','email','url') as $sTemp){
				if(isset($_POST['simple_grouptopiccomment_'.$sTemp])){
					$_REQUEST['grouptopiccomment_'.$sTemp]=$_POST['grouptopiccomment_'.$sTemp]=$_POST['simple_grouptopiccomment_'.$sTemp];
					unset($_POST['simple_grouptopiccomment_'.$sTemp]);
				}
			}
		}

		$sCommentContent=trim($_REQUEST['grouptopiccomment_message']);

		$arrOptions=$GLOBALS['_cache_']['home_option'];

		if($arrOptions['close_comment_feature']==1){
			$this->E(Dyhb::L('系统关闭了评论功能','__COMMON_LANG__@Function/Comment_Extend'));
		}

		if($arrOptions['seccode_comment_status']==1){
			$this->_oParentcontroller->check_seccode(true);
		}

		// IP禁止功能
		$sOnlineip=G::getIp();
		if(!Comment_Extend::banIp($sOnlineip)){
			$this->E(Dyhb::L('您的IP %s 已经被系统禁止发表评论','__COMMON_LANG__@Function/Comment_Extend',null,$sOnlineip));
		}

		// 评论名字检测
		$sCommentName=trim($_REQUEST['grouptopiccomment_name']);
		if(empty($sCommentName)){
			$this->E(Dyhb::L('评论名字不能为空','__COMMON_LANG__@Function/Comment_Extend'));
		}

		if(!Comment_Extend::commentName($sCommentName)){
			$this->E(Dyhb::L('此评论名字包含不可接受字符或被管理员屏蔽,请选择其它名字','__COMMON_LANG__@Function/Comment_Extend'));
		}

		// 评论内容长度检测
		$nCommentMinLen=intval($arrOptions['comment_min_len']);
		if(!Comment_Extend::commentMinLen($sCommentContent)){
			$this->E(Dyhb::L('评论内容最少的字节数为 %d','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentMinLen));
		}

		// 回帖小组自己设置最大长度限制
		$nCommentMaxLen=intval($GLOBALS['_cache_']['group_option']['comment_max_len']);
		if(!Comment_Extend::commentMaxLen($sCommentContent,$nCommentMaxLen)){
			$this->E(Dyhb::L('评论内容最大的字节数为 %d','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentMaxLen));
		}

		// 保存回复数据
		$oGrouptopiccomment=new GrouptopiccommentModel();
		
		// SPAM 垃圾信息阻止: URL数量限制
		$result=Comment_Extend::commentSpamUrl($sCommentContent);
		if($result===false){
			$nCommentSpamUrlNum=intval($arrOptions['comment_spam_url_num']);
			$this->E(Dyhb::L('评论内容中出现的链接数量超过了系统的限制 %d 条','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentSpamUrlNum));
		}
		if($result===0){
			$oGrouptopiccomment->grouptopiccomment_auditpass='0';
		}

		// SPAM 垃圾信息阻止: 屏蔽字符检测
		$result=Comment_Extend::commentSpamWords($sCommentContent);
		if($result===false){
			if(is_array($result)){
				$this->E(Dyhb::L("你的评论内容包含系统屏蔽的词语%s",'__COMMON_LANG__@Function/Comment_Extend',null,$result[1]));
			}
		}
		if($result===0){
			$oGrouptopiccomment->grouptopiccomment_auditpass='0';
		}

		// SPAM 垃圾信息阻止: 评论内容长度限制
		$nCommentSpamContentSize=intval($GLOBALS['_cache_']['group_option']['comment_spam_content_size']);
		$result=Comment_Extend::commentSpamContentsize($sCommentContent,$nCommentSpamContentSize);
		if($result===false){
			$this->E(Dyhb::L('评论内容最大的字节数为%d','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentSpamContentSize));
		}
		if($result===0){
			$oGrouptopiccomment->grouptopiccomment_auditpass='0';
		}

		// 发表评论间隔时间
		$nCommentPostSpace=intval($arrOptions['comment_post_space']);
		if($nCommentPostSpace){
			$oUserLastgrouptopiccomment=GrouptopiccommentModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->order('grouptopiccomment_id DESC')->getOne();

			if(!empty($oUserLastgrouptopiccomment['grouptopiccomment_id'])){
				$nLastPostTime=$oUserLastgrouptopiccomment['create_dateline'];
				if(!Comment_Extend::commentSpamPostSpace($nLastPostTime)){
					$this->E(Dyhb::L('为防止灌水,发表评论时间间隔为 %d 秒','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentPostSpace));
				}
			}
		}

		// 评论重复检测
		if($arrOptions['comment_repeat_check']){
			$nCurrentTimeStamp=CURRENT_TIMESTAMP;
			$oTryComment=GrouptopiccommentModel::F("grouptopiccomment_name=? AND grouptopiccomment_content=? AND {$nCurrentTimeStamp}-create_dateline<86400 AND grouptopiccomment_ip=?",$sCommentName,$sCommentContent,$sOnlineip)->order('grouptopiccomment_id DESC')->query();
			if(!empty($oTryComment['grouptopiccomment_id'])){
				$this->E(Dyhb::L('你提交的评论已经存在,24小时之内不允许出现相同的评论','__COMMON_LANG__@Function/Comment_Extend'));
			}
		}

		// 纯英文评论阻止
		$result=Comment_Extend::commentDisallowedallenglishword($sCommentContent);
		if($result===false){
			$this->E('You should type some Chinese word(like 你好)in your comment to pass the spam-check, thanks for your patience! '.Dyhb::L('您的评论中必须包含汉字','__COMMON_LANG__@Function/Comment_Extend'));
		}
		if($result===0){
			$oHomefreshcomment->homefreshcomment_status=0;
		}

		$arrParsecontent=Core_Extend::contentParsetag($sCommentContent);
		$sCommentContent=$arrParsecontent['content'];
		
		$oGrouptopiccomment->grouptopiccomment_content=$sCommentContent;
		$oGrouptopiccomment->grouptopic_id=$nId;
		
		// 发贴审核
		if($oGroup['group_auditcomment']==1){
			$oGrouptopiccomment->grouptopiccomment_auditpass='0';
		}

		$oGrouptopiccomment->save(0);

		if($oGrouptopiccomment->isError()){
			$this->E($oGrouptopiccomment->getErrorMessage());
		}

		// 更新积分
		Core_Extend::updateCreditByAction('group_addcomment',$GLOBALS['___login___']['user_id']);

		if($GLOBALS['___login___']['user_id']!=$oGrouptopiccomment['user_id']){
			Core_Extend::updateCreditByAction('group_topicreply',$oGrouptopiccomment['user_id']);
		}

		// 更新帖子的最后更新回复和帖子评论数量
		$arrLatestData=array(
			'commenttime'=>$oGrouptopiccomment->create_dateline,
			'commentid'=>$oGrouptopiccomment->grouptopiccomment_id,
			'tid'=>$oGrouptopic->grouptopic_id,
			'commentuserid'=>$GLOBALS['___login___']['user_id']
		);

		$oGrouptopic->grouptopic_latestcomment=serialize($arrLatestData);
		$oGrouptopic->grouptopic_comments=GrouptopiccommentModel::F('grouptopic_id=?',$nId)->all()->getCounts();
		$oGrouptopic->grouptopic_update=CURRENT_TIMESTAMP;
		$oGrouptopic->setAutofill(false);
		$oGrouptopic->save(0,'update');

		if($oGrouptopic->isError()){
			$this->E($oGrouptopic->getErrorMessage());
		}

		// 更新小组的最后更新数据
		$arrLatestData['commenttitle']=$oGrouptopic->grouptopic_title;
		$nCommnum=GrouptopicModel::F('group_id=?',$oGrouptopic->group_id)->getSum('grouptopic_comments');
		
		$oGroup->group_latestcomment=serialize($arrLatestData);
		$oGroup->group_topiccomment=$nCommnum;
		$oGroup->group_topiccommenttodaynum=$oGroup->group_topiccommenttodaynum+1;
		$oGroup->group_totaltodaynum=$oGroup->group_topictodaynum+$oGroup->group_topiccommenttodaynum;
		
		$oGroup->save(0,'update');

		if($oGroup->isError()){
			$this->E($oGroup->getErrorMessage());
		}

		// 保存小组今日数据
		GroupoptionModel::uploadOption('group_topiccommenttodaynum',$GLOBALS['_cache_']['group_option']['group_topiccommenttodaynum']+1);
		GroupoptionModel::uploadOption('group_totaltodaynum',$GLOBALS['_cache_']['group_option']['group_totaltodaynum']+1);

		$this->cache_site_();

		// 发送feed
		$sCommentLink='group://grouptopic/view?id='.$oGrouptopiccomment['grouptopic_id'].'&isolation_commentid='.$oGrouptopiccomment['grouptopiccomment_id'];
		$sCommentTitle=$oGrouptopic['grouptopic_title'];
		$sCommentMessage=$oGrouptopiccomment['grouptopiccomment_content'];

		try{
			Comment_Extend::addFeed(Dyhb::L('评论了帖子','Controller/Grouptopic'),'addgrouptopiccomment',$sCommentLink,$sCommentTitle,$sCommentMessage);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		// 发送提醒
		if($oGrouptopic['user_id']!=$GLOBALS['___login___']['user_id']){
			$sCommentLink='group://grouptopic/view?id='.$oGrouptopic['grouptopic_id'].'&isolation_commentid='.$oGrouptopiccomment['grouptopiccomment_id'];
			$sCommentTitle=$oGrouptopic['grouptopic_title'];
			$sCommentMessage=$oGrouptopiccomment['grouptopiccomment_content'];

			try{
				Comment_Extend::addNotice(Dyhb::L('评论了你的帖子','Controller/Grouptopic'),'addgrouptopiccomment',$sCommentLink,$sCommentTitle,$sCommentMessage,$oGrouptopic['user_id'],'grouptopiccomment',$oGrouptopic['grouptopic_id']);
			}catch(Exception $e){
				$this->E($e->getMessage());
			}
		}

		// 发送回帖被回复提醒
		if($oGrouptopiccomment['grouptopiccomment_parentid']>0){
			$oGrouptopiccommentParent=GrouptopiccommentModel::F('grouptopiccomment_id=?',$oGrouptopiccomment['grouptopiccomment_parentid'])->getOne();

			if(!empty($oGrouptopiccommentParent['grouptopiccomment_id']) && $oGrouptopiccommentParent['user_id']!=$GLOBALS['___login___']['user_id']){
				$sCommentLink='group://grouptopic/view?id='.$oGrouptopic['grouptopic_id'].'&isolation_commentid='.$oGrouptopiccomment['grouptopiccomment_id'];
				$sCommentTitle=$oGrouptopiccommentParent['grouptopiccomment_content'];
				$sCommentMessage=$oGrouptopiccomment['grouptopiccomment_content'];

				try{
					Comment_Extend::addNotice(Dyhb::L('评论了你的回帖','Controller/Grouptopic'),'replygrouptopiccomment',$sCommentLink,$sCommentTitle,$sCommentMessage,$oGrouptopiccommentParent['user_id'],'replygrouptopiccomment',$oGrouptopiccommentParent['grouptopic_id']);
				}catch(Exception $e){
					$this->E($e->getMessage());
				}
			}
		}

		// 发送评论@提醒
		if($arrParsecontent['atuserids']){
			foreach($arrParsecontent['atuserids'] as $nAtuserid){
				if($nAtuserid!=$GLOBALS['___login___']['user_id']){
					$sGrouptopiccommentmessage=Core_Extend::subString($oGrouptopiccomment['grouptopiccomment_content'],100,false,1,false);
					
					$sNoticetemplate='<div class="notice_atgrouptopiccomment"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('在主题回帖中提到了你','Controller/Grouptopic').'</span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{content_message}</span></div></div><div class="notice_action"><a href="{@grouptopiccomment_link}">'.Dyhb::L('查看','Controller/Grouptopic').'</a></div></div>';

					$arrNoticedata=array(
						'@space_link'=>'group://space@?id='.$GLOBALS['___login___']['user_id'],
						'user_name'=>$GLOBALS['___login___']['user_name'],
						'@grouptopiccomment_link'=>'group://grouptopic/view?id='.$oGrouptopiccomment['grouptopic_id'].'&isolation_commentid='.$oGrouptopiccomment['grouptopiccomment_id'],
						'content_message'=>$sGrouptopiccommentmessage,
					);

					try{
						Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$nAtuserid,'atgrouptopiccomment',$oGrouptopiccomment['grouptopiccomment_id']);
					}catch(Exception $e){
						$this->E($e->getMessage());
					}
				}
			}
		}

		$sUrl=Dyhb::U('group://topic@?id='.$oGrouptopic->grouptopic_id.'&isolation_commentid='.$oGrouptopiccomment->grouptopiccomment_id).'#grouptopiccomment-'.$oGrouptopiccomment->grouptopiccomment_id;

		$this->A(array('url'=>$sUrl),Dyhb::L('回复成功','Controller/Grouptopic'),1);
	}

	protected function cache_site_(){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache("group_site");
	}

}
