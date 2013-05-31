<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   附件添加评论($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入通用评论检测相关函数 */
require_once(Core_Extend::includeFile('function/Comment_Extend'));

class AddcommentController extends GlobalchildController{

	public function index(){
		try{
			Core_Extend::checkSpam();
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		// 读取附件数据
		$oAttachment=AttachmentModel::F('attachment_id=?',intval(G::getGpc('attachment_id')))->getOne();

		if(empty($oAttachment['attachment_id'])){
			$this->E(Dyhb::L('附件不存在','Controller'));
		}
		
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
		$sCommentName=trim(G::getGpc('attachmentcomment_name'));
		if(empty($sCommentName)){
			$this->E(Dyhb::L('评论名字不能为空','__COMMON_LANG__@Function/Comment_Extend'));
		}

		if(!Comment_Extend::commentName($sCommentName)){
			$this->E(Dyhb::L('此评论名字包含不可接受字符或被管理员屏蔽,请选择其它名字','__COMMON_LANG__@Function/Comment_Extend'));
		}

		// 评论内容长度检测
		$sCommentContent=G::cleanJs(strip_tags(trim(G::getGpc('attachmentcomment_content'))));
		$nCommentMinLen=intval($arrOptions['comment_min_len']);
		if(!Comment_Extend::commentMinLen($sCommentContent)){
			$this->E(Dyhb::L('评论内容最少的字节数为 %d','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentMinLen));
		}

		$nCommentMaxLen=intval($arrOptions['comment_max_len']);
		if(!Comment_Extend::commentMaxLen($sCommentContent)){
			$this->E(Dyhb::L('评论内容最大的字节数为 %d','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentMaxLen));
		}

		// 创建评论模型
		$oAttachmentcomment=new AttachmentcommentModel();

		// SPAM 垃圾信息阻止: URL数量限制
		$result=Comment_Extend::commentSpamUrl($sCommentContent);
		if($result===false){
			$nCommentSpamUrlNum=intval($arrOptions['comment_spam_url_num']);
			$this->E(Dyhb::L('评论内容中出现的链接数量超过了系统的限制 %d 条','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentSpamUrlNum));
		}
		if($result===0){
			$oAttachmentcomment->attachmentcomment_status=0;
		}

		// SPAM 垃圾信息阻止: 屏蔽字符检测
		$result=Comment_Extend::commentSpamWords($sCommentContent);
		if($result===false){
			if(is_array($result)){
				$this->E(Dyhb::L("你的评论内容包含系统屏蔽的词语%s",'__COMMON_LANG__@Function/Comment_Extend',null,$result[1]));
			}
		}
		if($result===0){
			$oAttachmentcomment->attachmentcomment_status=0;
		}

		// SPAM 垃圾信息阻止: 评论内容长度限制
		$result=Comment_Extend::commentSpamContentsize($sCommentContent);
		if($result===false){
			$nCommentSpamContentSize=intval($arrOptions['comment_spam_content_size']);
			$this->E(Dyhb::L('评论内容最大的字节数为%d','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentSpamContentSize));
		}
		if($result===0){
			$oAttachmentcomment->attachmentcomment_status=0;
		}

		// 发表评论间隔时间
		$nCommentPostSpace=intval($arrOptions['comment_post_space']);
		if($nCommentPostSpace){
			$oUserLastattachmentcomment=AttachmentcommentModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->order('attachmentcomment_id DESC')->getOne();

			if(!empty($oUserLastattachmentcomment['attachmentcomment_id'])){
				$nLastPostTime=$oUserLastattachmentcomment['create_dateline'];
				if(!Comment_Extend::commentSpamPostSpace($nLastPostTime)){
					$this->E(Dyhb::L('为防止灌水,发表评论时间间隔为 %d 秒','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentPostSpace));
				}
			}
		}

		// 评论重复检测
		if($arrOptions['comment_repeat_check']){
			$nCurrentTimeStamp=CURRENT_TIMESTAMP;
			$oTryComment=AttachmentcommentModel::F("attachmentcomment_name=? AND attachmentcomment_content=? AND {$nCurrentTimeStamp}-create_dateline<86400 AND attachmentcomment_ip=?",$sCommentName,$sCommentContent,$sOnlineip)->order('attachmentcomment_id DESC')->query();
			if(!empty($oTryComment['attachmentcomment_id'])){
				$this->E(Dyhb::L('你提交的评论已经存在,24小时之内不允许出现相同的评论','__COMMON_LANG__@Function/Comment_Extend'));
			}
		}

		// 纯英文评论阻止
		$result=Comment_Extend::commentDisallowedallenglishword($sCommentContent);
		if($result===false){
			$this->E('You should type some Chinese word(like 你好)in your comment to pass the spam-check, thanks for your patience! '.Dyhb::L('您的评论中必须包含汉字','__COMMON_LANG__@Function/Comment_Extend'));
		}
		if($result===0){
			$oAttachmentcomment->attachmentcomment_status=0;
		}

		// 评论审核
		if($arrOptions['audit_comment']==1){
			$oAttachmentcomment->attachmentcomment_auditpass=0;
		}

		// 保存评论数据
		$_POST=array_merge($_POST,$_GET);
		$oAttachmentcomment->safeInput();

		$arrParsecontent=Core_Extend::contentParsetag($sCommentContent);
		$sCommentContent=$arrParsecontent['content'];

		$oAttachmentcomment->attachmentcomment_content=$sCommentContent;
		$oAttachmentcomment->save(0);

		if($oAttachmentcomment->isError()){
			$this->E($oAttachmentcomment->getErrorMessage());
		}else{
			// 发送COOKIE
			Comment_Extend::sendCookie($oAttachmentcomment->attachmentcomment_name,$oAttachmentcomment->attachmentcomment_url,$oAttachmentcomment->attachmentcomment_email);

			// 更新评论数量
			$oAttachmentTemp=Dyhb::instance('AttachmentModel');
			$oAttachmentTemp->updateAttachmentcommentnum(intval(G::getGpc('attachment_id')));

			if($oAttachmentTemp->isError()){
				$oAttachmentTemp->getErrorMessage();
			}
			unset($oAttachmentTemp);

			// 发送feed
			$sCommentLink='home://file@?id='.$oAttachment['attachment_id'].'&isolation_commentid='.$oAttachmentcomment['attachmentcomment_id'];
			$sCommentTitle=$oAttachment['attachment_name'];
			$sCommentMessage=$oAttachmentcomment['attachmentcomment_content'];

			try{
				Comment_Extend::addFeed(Dyhb::L('评论了附件','Controller'),'addattachmentcomment',$sCommentLink,$sCommentTitle,$sCommentMessage,'[attachment]'.$oAttachment['attachment_id'].'[/attachment]');
			}catch(Exception $e){
				$this->E($e->getMessage());
			}

			// 发送提醒
			if($oAttachment['user_id']!=$GLOBALS['___login___']['user_id']){
				$sCommentLink='home://file@?id='.$oAttachment['attachment_id'].'&isolation_commentid='.$oAttachmentcomment['attachmentcomment_id'];
				$sCommentTitle=$oAttachment['attachment_name'];
				$sCommentMessage=$oAttachmentcomment['attachmentcomment_content'];

				try{
					Comment_Extend::addNotice(Dyhb::L('评论了附件','Controller'),'addattachmentcomment',$sCommentLink,$sCommentTitle,$sCommentMessage,$oAttachment['user_id'],'addattachmentcomment',$oAttachment['attachment_id'],'[attachment]'.$oAttachment['attachment_id'].'[/attachment]');
				}catch(Exception $e){
					$this->E($e->getMessage());
				}
			}

			// 发送评论被回复提醒
			if($oAttachmentcomment['attachmentcomment_parentid']>0){
				$oAttachmentcommentParent=AttachmentcommentModel::F('attachmentcomment_id=?',$oAttachmentcomment['attachmentcomment_parentid'])->getOne();

				if(!empty($oAttachmentcommentParent['attachmentcomment_id']) && $oAttachmentcommentParent['user_id']!=$GLOBALS['___login___']['user_id']){
					$sCommentLink='home://file@?id='.$oAttachment['attachment_id'].'&isolation_commentid='.$oAttachmentcomment['attachmentcomment_id'];
					$sCommentTitle=$oAttachmentcomment['attachmentcomment_content'];
					$sCommentMessage=$oAttachmentcomment['attachmentcomment_content'];

					try{
						Comment_Extend::addNotice(Dyhb::L('回复了你的评论','Controller'),'replyattachmentcomment',$sCommentLink,$sCommentTitle,$sCommentMessage,$oAttachmentcommentParent['user_id'],'replyattachmentcomment',$oAttachmentcommentParent['attachmentcomment_id']);
					}catch(Exception $e){
						$this->E($e->getMessage());
					}
				}
			}

			// 发送评论提醒
			if($arrParsecontent['atuserids']){
				foreach($arrParsecontent['atuserids'] as $nAtuserid){
					if($nAtuserid!=$GLOBALS['___login___']['user_id']){
						$sAttachmentcommentmessage=Core_Extend::subString($oAttachmentcomment['attachmentcomment_content'],100,false,1,false);
						
						$sNoticetemplate='<div class="notice_credit"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('在附件评论中提到了你','Controller').'</span><div class="notice_content">[attachment]'.$oAttachmentcomment['attachment_id'].'[/attachment]<div class="notice_quote"><span class="notice_quoteinfo">{content_message}</span></div></div><div class="notice_action"><a href="{@attachmentcomment_link}">'.Dyhb::L('查看','Controller').'</a></div></div>';

						$arrNoticedata=array(
							'@space_link'=>'home://space@?id='.$GLOBALS['___login___']['user_id'],
							'user_name'=>$GLOBALS['___login___']['user_name'],
							'@attachmentcomment_link'=>'home://file@?id='.$oAttachmentcomment['attachment_id'].'&isolation_commentid='.$oAttachmentcomment['attachmentcomment_id'],
							'content_message'=>$sAttachmentcommentmessage,
						);

						try{
							Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$nAtuserid,'atattachmentcomment',$oAttachmentcomment['attachmentcomment_id']);
						}catch(Exception $e){
							$this->E($e->getMessage());
						}
					}
				}
			}

			// 邮件通知
			try{
				$sCommentLink=Core_Extend::windsforceOuter('app=home&c=attachment&a=show&id='.$oAttachmentcomment->attachment_id.'&isolation_commentid='.$oAttachmentcomment['attachmentcomment_id']);

				$bHaveParentcomment=false;
				if($oAttachmentcomment->attachmentcomment_parentid==0){
					$nCommentParentIsreplymail=0;
					$oAttachmentCommentParent=AttachmentcommentModel::F('attachmentcomment_id=?',$oAttachmentcomment->attachmentcomment_parentid)->query();

					if($oAttachmentCommentParent['attachmentcomment_id']){
						$bHaveParentcomment=true;
					}
				}

				if($bHaveParentcomment===true){
					$nCommentParentIsreplymail=$oAttachmentCommentParent->attachmentcomment_isreplymail;
					$sCommentParentName=$oAttachmentCommentParent->attachmentcomment_name;
					$sCommentParentContent=$oAttachmentCommentParent->attachmentcomment_content;

					$sCommentParentLink=Core_Extend::windsforceOuter('app=home&c=attachment&a=show&id='.$oAttachmentCommentParent->attachment_id.'&isolation_commentid='.$oAttachmentCommentParent['attachmentcomment_id']);

					$sCommentParentEmail=$oAttachmentCommentParent->attachmentcomment_email;
					$sCommentParentUrl=$oAttachmentCommentParent->attachmentcomment_url;
				}else{
					$nCommentParentIsreplymail=0;
					$sCommentParentName=$sCommentParentContent=$sCommentParentLink=$sCommentParentEmail=$sCommentParentUrl='';
				}
				
				Comment_Extend::commentSendmail($oAttachmentcomment['attachmentcomment_name'],$oAttachmentcomment['attachmentcomment_content'],$sCommentLink,$oAttachmentcomment['attachmentcomment_email'],$oAttachmentcomment['attachmentcomment_url'],$nCommentParentIsreplymail,$sCommentParentEmail,$sCommentParentName,$sCommentParentContent,$sCommentParentLink,$sCommentParentEmail,$sCommentParentUrl);
			}catch(Exception $e){
				$this->E($e->getMessage());
			}
		}

		$arrCommentData=$oAttachmentcomment->toArray();

		$arrCommentData['jumpurl']=Dyhb::U('home://file@?id='.$oAttachmentcomment->attachment_id.'&isolation_commentid='.$oAttachmentcomment['attachmentcomment_id']).
				'#comment-'.$oAttachmentcomment['attachmentcomment_id'];

		// 更新积分
		Core_Extend::updateCreditByAction('commoncomment',$GLOBALS['___login___']['user_id']);

		$this->A($arrCommentData,Dyhb::L('添加附件评论成功','Controller'),1);
	}

}
