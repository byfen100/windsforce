<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动添加评论($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入通用评论检测相关函数 */
require_once(Core_Extend::includeFile('function/Comment_Extend'));

class AddeventcommentController extends GlobalchildController{

	public function index(){
		try{
			Core_Extend::checkSpam();
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		// 读取活动
		$oEvent=EventModel::F('event_id=?',intval(G::getGpc('event_id')))->getOne();

		if(empty($oEvent['event_id'])){
			$this->E(Dyhb::L('活动不存在','Controller'));
		}
		
		$arrOptions=$GLOBALS['_cache_']['home_option'];

		if($arrOptions['close_comment_feature']==1){
			$this->E(Dyhb::L('系统关闭了评论功能','__COMMON_LANG__@Common'));
		}

		if($arrOptions['seccode_comment_status']==1){
			$this->_oParentcontroller->check_seccode(true);
		}

		// IP禁止功能
		$sOnlineip=G::getIp();
		if(!Comment_Extend::banIp($sOnlineip)){
			$this->E(Dyhb::L('您的IP %s 已经被系统禁止发表评论','__COMMON_LANG__@Common',null,$sOnlineip));
		}

		// 评论名字检测
		$sCommentName=trim(G::getGpc('eventcomment_name'));
		if(empty($sCommentName)){
			$this->E(Dyhb::L('评论名字不能为空','__COMMON_LANG__@Common'));
		}

		if(!Comment_Extend::commentName($sCommentName)){
			$this->E(Dyhb::L('此评论名字包含不可接受字符或被管理员屏蔽,请选择其它名字','__COMMON_LANG__@Common'));
		}

		// 评论内容长度检测
		$sCommentContent=G::cleanJs(strip_tags(trim(G::getGpc('eventcomment_content'))));
		$nCommentMinLen=intval($arrOptions['comment_min_len']);
		if(!Comment_Extend::commentMinLen($sCommentContent)){
			$this->E(Dyhb::L('评论内容最少的字节数为 %d','__COMMON_LANG__@Common',null,$nCommentMinLen));
		}

		$nCommentMaxLen=intval($arrOptions['comment_max_len']);
		if(!Comment_Extend::commentMaxLen($sCommentContent)){
			$this->E(Dyhb::L('评论内容最大的字节数为 %d','__COMMON_LANG__@Common',null,$nCommentMaxLen));
		}

		// 创建评论模型
		$oEventcomment=new EventcommentModel();

		// SPAM 垃圾信息阻止: URL数量限制
		$result=Comment_Extend::commentSpamUrl($sCommentContent);
		if($result===false){
			$nCommentSpamUrlNum=intval($arrOptions['comment_spam_url_num']);
			$this->E(Dyhb::L('评论内容中出现的链接数量超过了系统的限制 %d 条','__COMMON_LANG__@Common',null,$nCommentSpamUrlNum));
		}
		if($result===0){
			$oEventcomment->eventcomment_status=0;
		}

		// SPAM 垃圾信息阻止: 屏蔽字符检测
		$result=Comment_Extend::commentSpamWords($sCommentContent);
		if($result===false){
			if(is_array($result)){
				$this->E(Dyhb::L("你的评论内容包含系统屏蔽的词语%s",'__COMMON_LANG__@Common',null,$result[1]));
			}
		}
		if($result===0){
			$oEventcomment->eventcomment_status=0;
		}

		// SPAM 垃圾信息阻止: 评论内容长度限制
		$result=Comment_Extend::commentSpamContentsize($sCommentContent);
		if($result===false){
			$nCommentSpamContentSize=intval($arrOptions['comment_spam_content_size']);
			$this->E(Dyhb::L('评论内容最大的字节数为%d','__COMMON_LANG__@Common',null,$nCommentSpamContentSize));
		}
		if($result===0){
			$oEventcomment->eventcomment_status=0;
		}

		// 发表评论间隔时间
		$nCommentPostSpace=intval($arrOptions['comment_post_space']);
		if($nCommentPostSpace){
			$oUserLasteventcomment=EventcommentModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->order('eventcomment_id DESC')->getOne();

			if(!empty($oUserLasteventcomment['eventcomment_id'])){
				$nLastPostTime=$oUserLasteventcomment['create_dateline'];
				if(!Comment_Extend::commentSpamPostSpace($nLastPostTime)){
					$this->E(Dyhb::L('为防止灌水,发表评论时间间隔为 %d 秒','__COMMON_LANG__@Common',null,$nCommentPostSpace));
				}
			}
		}

		// 评论重复检测
		if($arrOptions['comment_repeat_check']){
			$nCurrentTimeStamp=CURRENT_TIMESTAMP;
			$oTryComment=EventcommentModel::F("eventcomment_name=? AND eventcomment_content=? AND {$nCurrentTimeStamp}-create_dateline<86400 AND eventcomment_ip=?",$sCommentName,$sCommentContent,$sOnlineip)->order('eventcomment_id DESC')->query();
			if(!empty($oTryComment['eventcomment_id'])){
				$this->E(Dyhb::L('你提交的评论已经存在,24小时之内不允许出现相同的评论','__COMMON_LANG__@Common'));
			}
		}

		// 纯英文评论阻止
		$result=Comment_Extend::commentDisallowedallenglishword($sCommentContent);
		if($result===false){
			$this->E('You should type some Chinese word(like 你好)in your comment to pass the spam-check, thanks for your patience! '.Dyhb::L('您的评论中必须包含汉字','__COMMON_LANG__@Common'));
		}
		if($result===0){
			$oEventcomment->eventcomment_status=0;
		}

		// 评论审核
		if($arrOptions['audit_comment']==1){
			$oEventcomment->eventcomment_auditpass=0;
		}

		// 保存评论数据
		$_POST=array_merge($_POST,$_GET);
		$oEventcomment->safeInput();

		$arrParsecontent=Core_Extend::contentParsetag($sCommentContent);
		$sCommentContent=$arrParsecontent['content'];

		$oEventcomment->eventcomment_content=$sCommentContent;
		$oEventcomment->save(0);

		if($oEventcomment->isError()){
			$this->E($oEventcomment->getErrorMessage());
		}else{
			// 发送COOKIE
			Comment_Extend::sendCookie($oEventcomment->eventcomment_name,$oEventcomment->eventcomment_url,$oEventcomment->eventcomment_email);

			// 更新评论数量
			$oEventTemp=Dyhb::instance('EventModel');
			$oEventTemp->updateEventcommentnum(intval(G::getGpc('event_id')));

			if($oEventTemp->isError()){
				$oEventTemp->getErrorMessage();
			}
			unset($oEventTemp);

			// 发送feed
			$sCommentLink='event://e@?id='.$oEvent['event_id'].'&isolation_commentid='.$oEventcomment['eventcomment_id'];
			$sCommentTitle=$oEvent['event_title'];
			$sCommentMessage=$oEventcomment['eventcomment_content'];

			try{
				Comment_Extend::addFeed(Dyhb::L('评论了活动','Controller'),'addeventcomment',$sCommentLink,$sCommentTitle,$sCommentMessage);
			}catch(Exception $e){
				$this->E($e->getMessage());
			}

			// 发送提醒
			if($oEvent['user_id']!=$GLOBALS['___login___']['user_id']){
				$sCommentLink='event://e@?id='.$oEvent['event_id'].'&isolation_commentid='.$oEventcomment['eventcomment_id'];
				$sCommentTitle=$oEvent['event_name'];
				$sCommentMessage=$oEventcomment['eventcomment_content'];

				try{
					Comment_Extend::addNotice(Dyhb::L('评论了活动','Controller'),'addeventcomment',$sCommentLink,$sCommentTitle,$sCommentMessage,$oEvent['user_id'],'addeventcomment',$oEvent['event_id']);
				}catch(Exception $e){
					$this->E($e->getMessage());
				}
			}

			// 发送评论被回复提醒
			if($oEventcomment['eventcomment_parentid']>0){
				$oEventcommentParent=EventcommentModel::F('eventcomment_id=?',$oEventcomment['eventcomment_parentid'])->getOne();

				if(!empty($oEventcommentParent['eventcomment_id']) && $oEventcommentParent['user_id']!=$GLOBALS['___login___']['user_id']){
					$sCommentLink='event://e@?id='.$oEvent['event_id'].'&isolation_commentid='.$oEventcomment['eventcomment_id'];
					$sCommentTitle=$oEventcomment['eventcomment_content'];
					$sCommentMessage=$oEventcomment['eventcomment_content'];

					try{
						Comment_Extend::addNotice(Dyhb::L('回复了你的评论','Controller'),'replyeventcomment',$sCommentLink,$sCommentTitle,$sCommentMessage,$oEventcommentParent['user_id'],'replyeventcomment',$oEventcommentParent['eventcomment_id']);
					}catch(Exception $e){
						$this->E($e->getMessage());
					}
				}
			}

			// 发送评论提醒
			if($arrParsecontent['atuserids']){
				foreach($arrParsecontent['atuserids'] as $nAtuserid){
					if($nAtuserid!=$GLOBALS['___login___']['user_id']){
						$sEventcommentmessage=Core_Extend::subString($oEventcomment['eventcomment_content'],100,false,1,false);
						
						$sNoticetemplate='<div class="notice_credit"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('在活动评论中提到了你','Controller').'</span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{content_message}</span></div></div><div class="notice_action"><a href="{@eventcomment_link}">'.Dyhb::L('查看','Controller').'</a></div></div>';

						$arrNoticedata=array(
							'@space_link'=>'home://space@?id='.$GLOBALS['___login___']['user_id'],
							'user_name'=>$GLOBALS['___login___']['user_name'],
							'@eventcomment_link'=>'event://e@?id='.$oEventcomment['event_id'].'&isolation_commentid='.$oEventcomment['eventcomment_id'],
							'content_message'=>$sEventcommentmessage,
						);

						try{
							Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$nAtuserid,'ateventcomment',$oEventcomment['eventcomment_id']);
						}catch(Exception $e){
							$this->E($e->getMessage());
						}
					}
				}
			}

			// 邮件通知
			try{
				$sCommentLink=Core_Extend::windsforceOuter('app=event&c=event&a=show&id='.$oEventcomment->event_id.'&isolation_commentid='.$oEventcomment['eventcomment_id']);

				$bHaveParentcomment=false;
				if($oEventcomment->eventcomment_parentid==0){
					$nCommentParentIsreplymail=0;
					$oEventCommentParent=EventcommentModel::F('eventcomment_id=?',$oEventcomment->eventcomment_parentid)->query();

					if($oEventCommentParent['eventcomment_id']){
						$bHaveParentcomment=true;
					}
				}

				if($bHaveParentcomment===true){
					$nCommentParentIsreplymail=$oEventCommentParent->eventcomment_isreplymail;
					$sCommentParentName=$oEventCommentParent->eventcomment_name;
					$sCommentParentContent=$oEventCommentParent->eventcomment_content;

					$sCommentParentLink=Core_Extend::windsforceOuter('app=event&c=event&a=show&id='.$oEventCommentParent->event_id.'&isolation_commentid='.$oEventCommentParent['eventcomment_id']);

					$sCommentParentEmail=$oEventCommentParent->eventcomment_email;
					$sCommentParentUrl=$oEventCommentParent->eventcomment_url;
				}else{
					$nCommentParentIsreplymail=0;
					$sCommentParentName=$sCommentParentContent=$sCommentParentLink=$sCommentParentEmail=$sCommentParentUrl='';
				}
				
				Comment_Extend::commentSendmail($oEventcomment['eventcomment_name'],$oEventcomment['eventcomment_content'],$sCommentLink,$oEventcomment['eventcomment_email'],$oEventcomment['eventcomment_url'],$nCommentParentIsreplymail,$sCommentParentEmail,$sCommentParentName,$sCommentParentContent,$sCommentParentLink,$sCommentParentEmail,$sCommentParentUrl);
			}catch(Exception $e){
				$this->E($e->getMessage());
			}
		}

		$arrCommentData=$oEventcomment->toArray();

		$arrCommentData['jumpurl']=Dyhb::U('event://e@?id='.$oEventcomment->event_id.'&isolation_commentid='.$oEventcomment['eventcomment_id']).
				'#comment-'.$oEventcomment['eventcomment_id'];

		// 更新积分
		Core_Extend::updateCreditByAction('commoncomment',$GLOBALS['___login___']['user_id']);

		$this->A($arrCommentData,Dyhb::L('添加活动评论成功','Controller'),1);
	}

}
