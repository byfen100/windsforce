<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加评论($Liu.XiangMin)*/

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

		// 读取新鲜事数据
		$oHomefresh=HomefreshModel::F('homefresh_id=?',intval(G::getGpc('homefresh_id')))->getOne();

		if(empty($oHomefresh['homefresh_id'])){
			$this->E(Dyhb::L('新鲜事不存在','Controller/Homefresh'));
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
		$sCommentName=trim(G::getGpc('homefreshcomment_name'));
		if(empty($sCommentName)){
			$this->E(Dyhb::L('评论名字不能为空','__COMMON_LANG__@Function/Comment_Extend'));
		}

		if(!Comment_Extend::commentName($sCommentName)){
			$this->E(Dyhb::L('此评论名字包含不可接受字符或被管理员屏蔽,请选择其它名字','__COMMON_LANG__@Function/Comment_Extend'));
		}

		// 评论内容长度检测
		$sCommentContent=G::cleanJs(strip_tags(trim(G::getGpc('homefreshcomment_content'))));
		$nCommentMinLen=intval($arrOptions['comment_min_len']);
		if(!Comment_Extend::commentMinLen($sCommentContent)){
			$this->E(Dyhb::L('评论内容最少的字节数为 %d','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentMinLen));
		}

		$nCommentMaxLen=intval($arrOptions['comment_max_len']);
		if(!Comment_Extend::commentMaxLen($sCommentContent)){
			$this->E(Dyhb::L('评论内容最大的字节数为 %d','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentMaxLen));
		}

		// 创建评论模型
		$oHomefreshcomment=new HomefreshcommentModel();

		// SPAM 垃圾信息阻止: URL数量限制
		$result=Comment_Extend::commentSpamUrl($sCommentContent);
		if($result===false){
			$nCommentSpamUrlNum=intval($arrOptions['comment_spam_url_num']);
			$this->E(Dyhb::L('评论内容中出现的链接数量超过了系统的限制 %d 条','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentSpamUrlNum));
		}
		if($result===0){
			$oHomefreshcomment->homefreshcomment_status=0;
		}

		// SPAM 垃圾信息阻止: 屏蔽字符检测
		$result=Comment_Extend::commentSpamWords($sCommentContent);
		if($result===false){
			if(is_array($result)){
				$this->E(Dyhb::L("你的评论内容包含系统屏蔽的词语%s",'__COMMON_LANG__@Function/Comment_Extend',null,$result[1]));
			}
		}
		if($result===0){
			$oHomefreshcomment->homefreshcomment_status=0;
		}

		// SPAM 垃圾信息阻止: 评论内容长度限制
		$result=Comment_Extend::commentSpamContentsize($sCommentContent);
		if($result===false){
			$nCommentSpamContentSize=intval($arrOptions['comment_spam_content_size']);
			$this->E(Dyhb::L('评论内容最大的字节数为%d','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentSpamContentSize));
		}
		if($result===0){
			$oHomefreshcomment->homefreshcomment_status=0;
		}

		// 发表评论间隔时间
		$nCommentPostSpace=intval($arrOptions['comment_post_space']);
		if($nCommentPostSpace){
			$oUserLasthomefreshcomment=HomefreshcommentModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->order('homefreshcomment_id DESC')->getOne();

			if(!empty($oUserLasthomefreshcomment['homefreshcomment_id'])){
				$nLastPostTime=$oUserLasthomefreshcomment['create_dateline'];
				if(!Comment_Extend::commentSpamPostSpace($nLastPostTime)){
					$this->E(Dyhb::L('为防止灌水,发表评论时间间隔为 %d 秒','__COMMON_LANG__@Function/Comment_Extend',null,$nCommentPostSpace));
				}
			}
		}

		// 评论重复检测
		if($arrOptions['comment_repeat_check']){
			$nCurrentTimeStamp=CURRENT_TIMESTAMP;
			$oTryComment=HomefreshcommentModel::F("homefreshcomment_name=? AND homefreshcomment_content=? AND {$nCurrentTimeStamp}-create_dateline<86400 AND homefreshcomment_ip=?",$sCommentName,$sCommentContent,$sOnlineip)->order('homefreshcomment_id DESC')->query();
			if(!empty($oTryComment['homefreshcomment_id'])){
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

		// 评论审核
		if($arrOptions['audit_comment']==1){
			$oHomefreshcomment->homefreshcomment_auditpass=0;
		}

		// 保存评论数据
		$_POST=array_merge($_POST,$_GET);
		$oHomefreshcomment->safeInput();

		$arrParsecontent=Core_Extend::contentParsetag($sCommentContent);
		$sCommentContent=$arrParsecontent['content'];

		$oHomefreshcomment->homefreshcomment_content=$sCommentContent;
		$oHomefreshcomment->save(0);

		if($oHomefreshcomment->isError()){
			$this->E($oHomefreshcomment->getErrorMessage());
		}else{
			// 发送COOKIE
			Comment_Extend::sendCookie($oHomefreshcomment->homefreshcomment_name,$oHomefreshcomment->homefreshcomment_url,$oHomefreshcomment->homefreshcomment_email);

			// 更新评论数量
			$oHomefreshTemp=Dyhb::instance('HomefreshModel');
			$oHomefreshTemp->updateHomefreshcommentnum(intval(G::getGpc('homefresh_id')));

			if($oHomefreshTemp->isError()){
				$oHomefreshTemp->getErrorMessage();
			}
			unset($oHomefreshTemp);

			// 发送feed
			$sCommentLink='home://fresh@?id='.$oHomefresh['homefresh_id'].'&isolation_commentid='.$oHomefreshcomment['homefreshcomment_id'];
			$sCommentTitle=$oHomefresh['homefresh_title']?$oHomefresh['homefresh_title']:strip_tags($oHomefresh['homefresh_message']);
			$sCommentTitle=G::subString($sCommentTitle,0,30);
			$sCommentMessage=G::subString(strip_tags($oHomefreshcomment['homefreshcomment_content']),0,100);

			try{
				Comment_Extend::addFeed(Dyhb::L('评论了新鲜事','Controller/Homefresh'),'addhomefreshcomment',$sCommentLink,$sCommentTitle,$sCommentMessage);
			}catch(Exception $e){
				$this->E($e->getMessage());
			}

			// 发送提醒
			if($oHomefresh['user_id']!=$GLOBALS['___login___']['user_id']){
				$sCommentLink='home://fresh@?id='.$oHomefresh['homefresh_id'].'&isolation_commentid='.$oHomefreshcomment['homefreshcomment_id'];
				$sCommentTitle=$oHomefresh['homefresh_title']?$oHomefresh['homefresh_title']:strip_tags($oHomefresh['homefresh_message']);
				$sCommentTitle=G::subString($sCommentTitle,0,30);
				$sCommentMessage=G::subString(strip_tags($oHomefreshcomment['homefreshcomment_content']),0,100);

				try{
					Comment_Extend::addNotice(Dyhb::L('评论了你的新鲜事','Controller/Homefresh'),'addhomefreshcomment',$sCommentLink,$sCommentTitle,$sCommentMessage,$oHomefresh['user_id'],'homefreshcomment',$oHomefresh['homefresh_id']);
				}catch(Exception $e){
					$this->E($e->getMessage());
				}
			}

			// 发送评论被回复提醒
			if($oHomefreshcomment['homefreshcomment_parentid']>0){
				$oHomefreshcommentParent=HomefreshcommentModel::F('homefreshcomment_id=?',$oHomefreshcomment['homefreshcomment_parentid'])->getOne();

				if(!empty($oHomefreshcommentParent['homefreshcomment_id']) && $oHomefreshcommentParent['user_id']!=$GLOBALS['___login___']['user_id']){
					$sCommentLink='home://fresh@?id='.$oHomefresh['homefresh_id'].'&isolation_commentid='.$oHomefreshcomment['homefreshcomment_id'];
					$sCommentTitle=G::subString(strip_tags($oHomefreshcomment['homefreshcomment_content']),0,30);
					$sCommentMessage=G::subString(strip_tags($oHomefreshcomment['homefreshcomment_content']),0,100);

					try{
						Comment_Extend::addNotice(Dyhb::L('回复了你的评论','Controller/Homefresh'),'replyhomefreshcomment',$sCommentLink,$sCommentTitle,$sCommentMessage,$oHomefreshcommentParent['user_id'],'replyhomefreshcomment',$oHomefreshcommentParent['homefreshcomment_id']);
					}catch(Exception $e){
						$this->E($e->getMessage());
					}
				}
			}

			// 发送评论提醒
			if($arrParsecontent['atuserids']){
				foreach($arrParsecontent['atuserids'] as $nAtuserid){
					if($nAtuserid!=$GLOBALS['___login___']['user_id']){
						$sHomefreshcommentmessage=G::subString(strip_tags($oHomefreshcomment['homefreshcomment_content']),0,100);
						
						$sNoticetemplate='<div class="notice_athomefreshcomment"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('在新鲜事评论中提到了你','Controller/Homefresh').'</span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{content_message}</span></div></div><div class="notice_action"><a href="{@homefreshcomment_link}">'.Dyhb::L('查看','Controller/Homefresh').'</a></div></div>';

						$arrNoticedata=array(
							'@space_link'=>'home://space@?id='.$GLOBALS['___login___']['user_id'],
							'user_name'=>$GLOBALS['___login___']['user_name'],
							'@homefreshcomment_link'=>'home://fresh@?id='.$oHomefreshcomment['homefresh_id'].'&isolation_commentid='.$oHomefreshcomment['homefreshcomment_id'],
							'content_message'=>$sHomefreshcommentmessage,
						);

						try{
							Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$nAtuserid,'athomefreshcomment',$oHomefreshcomment['homefreshcomment_id']);
						}catch(Exception $e){
							$this->E($e->getMessage());
						}
					}
				}
			}

			// 邮件通知
			try{
				$sCommentLink=Core_Extend::windsforceOuter('app=home&c=ucenter&a=view&id='.$oHomefreshcomment->homefresh_id.'&isolation_commentid='.$oHomefreshcomment['homefreshcomment_id']);

				$bHaveParentcomment=false;
				if($oHomefreshcomment->homefreshcomment_parentid==0){
					$nCommentParentIsreplymail=0;
					$oHomefreshCommentParent=HomefreshcommentModel::F('homefreshcomment_id=?',$oHomefreshcomment->homefreshcomment_parentid)->query();

					if($oHomefreshCommentParent['homefreshcomment_id']){
						$bHaveParentcomment=true;
					}
				}

				if($bHaveParentcomment===true){
					$nCommentParentIsreplymail=$oHomefreshCommentParent->homefreshcomment_isreplymail;
					$sCommentParentName=$oHomefreshCommentParent->homefreshcomment_name;
					$sCommentParentContent=$oHomefreshCommentParent->homefreshcomment_content;

					$sCommentParentLink=Core_Extend::windsforceOuter('app=home&c=ucenter&a=view&id='.$oHomefreshCommentParent->homefresh_id.'&isolation_commentid='.$oHomefreshCommentParent['homefreshcomment_id']);

					$sCommentParentEmail=$oHomefreshCommentParent->homefreshcomment_email;
					$sCommentParentUrl=$oHomefreshCommentParent->homefreshcomment_url;
				}else{
					$nCommentParentIsreplymail=0;
					$sCommentParentName=$sCommentParentContent=$sCommentParentLink=$sCommentParentEmail=$sCommentParentUrl='';
				}
				
				Comment_Extend::commentSendmail($oHomefreshcomment['homefreshcomment_name'],$oHomefreshcomment['homefreshcomment_content'],$sCommentLink,$oHomefreshcomment['homefreshcomment_email'],$oHomefreshcomment['homefreshcomment_url'],$nCommentParentIsreplymail,$sCommentParentEmail,$sCommentParentName,$sCommentParentContent,$sCommentParentLink,$sCommentParentEmail,$sCommentParentUrl);
			}catch(Exception $e){
				$this->E($e->getMessage());
			}
		}

		$arrCommentData=$oHomefreshcomment->toArray();

		$nQuick=intval(G::getGpc('quick','G'));
		if($nQuick==1){
			$arrCommentData['homefreshcomment_content']=Core_Extend::ubb(G::subString(strip_tags($arrCommentData['homefreshcomment_content']),0,$GLOBALS['_cache_']['home_option']['homefreshcomment_substring_num']),false);
			$arrCommentData['comment_name']=UserModel::getUsernameById($oHomefreshcomment->user_id);
			$arrCommentData['create_dateline']=Core_Extend::timeFormat($arrCommentData['create_dateline']);
			$arrCommentData['avatar']=Core_Extend::avatar($arrCommentData['user_id'],'small');
			$arrCommentData['url']=Dyhb::U('home://space@?id='.$arrCommentData['user_id']);
			$arrCommentData['num']=$oHomefresh->homefresh_commentnum;
			$arrCommentData['viewurl']=Dyhb::U('home://fresh@?id='.$arrCommentData['homefresh_id'].'&isolation_commentid='.$arrCommentData['homefreshcomment_id']);
			$arrCommentData['usericon']=Core_Extend::getUsericon($arrCommentData['user_id']);
		}else{
			$arrCommentData['jumpurl']=Dyhb::U('home://fresh@?id='.$arrCommentData['homefresh_id'].'&isolation_commentid='.$arrCommentData['homefreshcomment_id']).
				'#comment-'.$oHomefreshcomment['homefreshcomment_id'];
		}

		// 保存home今日数据
		OptionModel::uploadOption('todayhomefreshcommentnum',$GLOBALS['_option_']['todayhomefreshcommentnum']+1);
		OptionModel::uploadOption('todaytotalnum',$GLOBALS['_option_']['todaytotalnum']+1);

		$this->cache_site_();

		// 更新积分
		Core_Extend::updateCreditByAction('commoncomment',$GLOBALS['___login___']['user_id']);

		$this->A($arrCommentData,Dyhb::L('添加新鲜事评论成功','Controller/Homefresh'),1);
	}

	protected function cache_site_(){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache("site");
	}

}
