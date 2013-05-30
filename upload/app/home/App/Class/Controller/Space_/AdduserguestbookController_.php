<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户留言板($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入通用评论检测相关函数 */
require_once(Core_Extend::includeFile('function/Comment_Extend'));

class AdduserguestbookController extends GlobalchildController{

	public function index(){
		if($GLOBALS['___login___']===false){
			$this->E(Dyhb::L('你没有登录，无法留言','Controller'));
		}
		
		try{
			Core_Extend::checkSpam();
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		// 读取用户数据
		$nUserguestbookuserid=intval(G::getGpc('userguestbook_userid'));
		$oUser=UserModel::F('user_id=?',$nUserguestbookuserid)->getOne();

		if(empty($oUser['user_id'])){
			$this->E(Dyhb::L('用户不存在','Controller'));
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
		$sCommentName=trim(G::getGpc('userguestbook_name'));
		if(empty($sCommentName)){
			$this->E(Dyhb::L('评论名字不能为空','__COMMON_LANG__@Function/Comment_Extend'));
		}

		if(!Comment_Extend::commentName($sCommentName)){
			$this->E(Dyhb::L('此评论名字包含不可接受字符或被管理员屏蔽,请选择其它名字','__COMMON_LANG__@Function/Comment_Extend'));
		}

		// 评论内容长度检测
		$sCommentContent=G::cleanJs(strip_tags(trim(G::getGpc('userguestbook_content'))));
		$nCommentMinLen=intval($arrOptions['comment_min_len']);
		if(!Comment_Extend::commentMinLen($sCommentContent)){
			$this->E(Dyhb::L('评论内容最少的字节数为 %d','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentMinLen));
		}

		$nCommentMaxLen=intval($arrOptions['comment_max_len']);
		if(!Comment_Extend::commentMaxLen($sCommentContent)){
			$this->E(Dyhb::L('评论内容最大的字节数为 %d','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentMaxLen));
		}

		// 创建评论模型
		$oUserguestbook=new UserguestbookModel();

		// SPAM 垃圾信息阻止: URL数量限制
		$result=Comment_Extend::commentSpamUrl($sCommentContent);
		if($result===false){
			$nCommentSpamUrlNum=intval($arrOptions['comment_spam_url_num']);
			$this->E(Dyhb::L('评论内容中出现的链接数量超过了系统的限制 %d 条','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentSpamUrlNum));
		}
		if($result===0){
			$oUserguestbook->userguestbook_status=0;
		}

		// SPAM 垃圾信息阻止: 屏蔽字符检测
		$result=Comment_Extend::commentSpamWords($sCommentContent);
		if($result===false){
			if(is_array($result)){
				$this->E(Dyhb::L("你的评论内容包含系统屏蔽的词语%s",'__COMMON_LANG__@Function/Comment_Extend',null,$result[1]));
			}
		}
		if($result===0){
			$oUserguestbook->userguestbook_status=0;
		}

		// SPAM 垃圾信息阻止: 评论内容长度限制
		$result=Comment_Extend::commentSpamContentsize($sCommentContent);
		if($result===false){
			$nCommentSpamContentSize=intval($arrOptions['comment_spam_content_size']);
			$this->E(Dyhb::L('评论内容最大的字节数为%d','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentSpamContentSize));
		}
		if($result===0){
			$oUserguestbook->userguestbook_status=0;
		}

		// 发表评论间隔时间
		$nCommentPostSpace=intval($arrOptions['comment_post_space']);
		if($nCommentPostSpace){
			$oUserLastuserguestbook=UserguestbookModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->order('userguestbook_id DESC')->getOne();

			if(!empty($oUserLastuserguestbook['userguestbook_id'])){
				$nLastPostTime=$oUserLastuserguestbook['create_dateline'];
				if(!Comment_Extend::commentSpamPostSpace($nLastPostTime)){
					$this->E(Dyhb::L('为防止灌水,发表评论时间间隔为 %d 秒','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentPostSpace));
				}
			}
		}

		// 评论重复检测
		if($arrOptions['comment_repeat_check']){
			$nCurrentTimeStamp=CURRENT_TIMESTAMP;
			$oTryComment=UserguestbookModel::F("userguestbook_name=? AND userguestbook_content=? AND {$nCurrentTimeStamp}-create_dateline<86400 AND userguestbook_ip=?",$sCommentName,$sCommentContent,$sOnlineip)->order('userguestbook_id DESC')->query();
			if(!empty($oTryComment['userguestbook_id'])){
				$this->E(Dyhb::L('你提交的评论已经存在,24小时之内不允许出现相同的评论','__COMMON_LANG__@Function/Comment_Extend'));
			}
		}

		// 纯英文评论阻止
		$result=Comment_Extend::commentDisallowedallenglishword($sCommentContent);
		if($result===false){
			$this->E('You should type some Chinese word(like 你好)in your comment to pass the spam-check, thanks for your patience! '.Dyhb::L('您的评论中必须包含汉字','__COMMON_LANG__@Function/Comment_Extend'));
		}
		if($result===0){
			$oUserguestbook->userguestbook_status=0;
		}

		// 评论审核
		if($arrOptions['audit_comment']==1){
			$oUserguestbook->userguestbook_auditpass=0;
		}

		// 保存评论数据
		$_POST=array_merge($_POST,$_GET);
		$oUserguestbook->safeInput();

		$arrParsecontent=Core_Extend::contentParsetag($sCommentContent);
		$sCommentContent=$arrParsecontent['content'];

		$oUserguestbook->userguestbook_content=$sCommentContent;
		$oUserguestbook->save(0);

		if($oUserguestbook->isError()){
			$this->E($oUserguestbook->getErrorMessage());
		}else{
			// 发送COOKIE
			Comment_Extend::sendCookie($oUserguestbook->userguestbook_name,$oUserguestbook->userguestbook_url,$oUserguestbook->userguestbook_email);

			// 发送feed
			$sCommentLink='home://space@?id='.$oUser['user_id'].'&type=guestbook&isolation_commentid='.$oUserguestbook['userguestbook_id'];
			$sCommentTitle=$oUser['user_name'];
			$sCommentMessage=$oUserguestbook['userguestbook_content'];

			try{
				Comment_Extend::addFeed(Dyhb::L('给你留言了','Controller'),'adduserguestbook',$sCommentLink,$sCommentTitle,$sCommentMessage);
			}catch(Exception $e){
				$this->E($e->getMessage());
			}

			// 发送提醒
			if($oUser['user_id']!=$GLOBALS['___login___']['user_id']){
				$sCommentLink='home://space@?id='.$oUser['user_id'].'&type=guestbook&isolation_commentid='.$oUserguestbook['userguestbook_id'];
				$sCommentTitle=$oUser['user_name'];
				$sCommentMessage=$oUserguestbook['userguestbook_content'];

				try{
					Comment_Extend::addNotice(Dyhb::L('给你留言了','Controller'),'adduserguestbook',$sCommentLink,$sCommentTitle,$sCommentMessage,$oUser['user_id'],'adduserguestbook',$oUser['user_id']);
				}catch(Exception $e){
					$this->E($e->getMessage());
				}
			}

			// 发送评论被回复提醒
			if($oUserguestbook['userguestbook_parentid']>0){
				$oUserguestbookParent=UserguestbookModel::F('userguestbook_id=?',$oUserguestbook['userguestbook_parentid'])->getOne();

				if(!empty($oUserguestbookParent['userguestbook_id']) && $oUserguestbookParent['user_id']!=$GLOBALS['___login___']['user_id']){
					$sCommentLink='home://space@?id='.$oUser['user_id'].'&type=guestbook&isolation_commentid='.$oUserguestbook['userguestbook_id'];
					$sCommentTitle=$oUserguestbook['userguestbook_content'];
					$sCommentMessage=$oUserguestbook['userguestbook_content'];

					try{
						Comment_Extend::addNotice(Dyhb::L('回复了你的评论','Controller'),'replyuserguestbook',$sCommentLink,$sCommentTitle,$sCommentMessage,$oUserguestbookParent['user_id'],'replyuserguestbook',$oUserguestbookParent['userguestbook_id']);
					}catch(Exception $e){
						$this->E($e->getMessage());
					}
				}
			}

			// 发送评论提醒
			if($arrParsecontent['atuserids']){
				foreach($arrParsecontent['atuserids'] as $nAtuserid){
					if($nAtuserid!=$GLOBALS['___login___']['user_id']){
						$sUserguestbookmessage=Core_Extend::subString($oUserguestbook['userguestbook_content'],100,false,1,false);
						
						$sNoticetemplate='<div class="notice_credit"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('在留言中中提到了你','Controller').'</span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{content_message}</span></div></div><div class="notice_action"><a href="{@userguestbook_link}">'.Dyhb::L('查看','Controller').'</a></div></div>';

						$arrNoticedata=array(
							'@space_link'=>'home://space@?id='.$GLOBALS['___login___']['user_id'],
							'user_name'=>$GLOBALS['___login___']['user_name'],
							'@userguestbook_link'=>'home://space@?id='.$oUserguestbook['userguestbook_userid'].'&type=guestbook&isolation_commentid='.$oUserguestbook['userguestbook_id'],
							'content_message'=>$sUserguestbookmessage,
						);

						try{
							Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$nAtuserid,'atuserguestbook',$oUserguestbook['userguestbook_id']);
						}catch(Exception $e){
							$this->E($e->getMessage());
						}
					}
				}
			}

			// 邮件通知
			try{
				$sCommentLink=Core_Extend::windsforceOuter('app=home&c=space&a=index&id='.$oUserguestbook->userguestbook_id.'&type=guestbook&isolation_commentid='.$oUserguestbook['userguestbook_id']);

				$bHaveParentcomment=false;
				if($oUserguestbook->userguestbook_parentid==0){
					$nCommentParentIsreplymail=0;
					$oUserguestbookParent=UserguestbookModel::F('userguestbook_id=?',$oUserguestbook->userguestbook_parentid)->query();

					if($oUserguestbookParent['userguestbook_id']){
						$bHaveParentcomment=true;
					}
				}

				if($bHaveParentcomment===true){
					$nCommentParentIsreplymail=$oUserguestbookParent->userguestbook_isreplymail;
					$sCommentParentName=$oUserguestbookParent->userguestbook_name;
					$sCommentParentContent=$oUserguestbookParent->userguestbook_content;

					$sCommentParentLink=Core_Extend::windsforceOuter('app=home&c=space&a=index&id='.$oUserguestbookParent->userguestbook_id.'&type=guestbook&isolation_commentid='.$oUserguestbookParent['userguestbook_id']);

					$sCommentParentEmail=$oUserguestbookParent->userguestbook_email;
					$sCommentParentUrl=$oUserguestbookParent->userguestbook_url;
				}else{
					$nCommentParentIsreplymail=0;
					$sCommentParentName=$sCommentParentContent=$sCommentParentLink=$sCommentParentEmail=$sCommentParentUrl='';
				}
				
				Comment_Extend::commentSendmail($oUserguestbook['userguestbook_name'],$oUserguestbook['userguestbook_content'],$sCommentLink,$oUserguestbook['userguestbook_email'],$oUserguestbook['userguestbook_url'],$nCommentParentIsreplymail,$sCommentParentEmail,$sCommentParentName,$sCommentParentContent,$sCommentParentLink,$sCommentParentEmail,$sCommentParentUrl);
			}catch(Exception $e){
				$this->E($e->getMessage());
			}
		}

		$arrCommentData=$oUserguestbook->toArray();

		$arrCommentData['jumpurl']=Dyhb::U('home://space@?id='.$nUserguestbookuserid.'&type=guestbook&isolation_commentid='.$oUserguestbook['userguestbook_id']).
				'#comment-'.$oUserguestbook['userguestbook_id'];

		// 更新积分
		Core_Extend::updateCreditByAction('commoncomment',$GLOBALS['___login___']['user_id']);

		$this->A($arrCommentData,Dyhb::L('添加留言成功','Controller'),1);
	}

}
