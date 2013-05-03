<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   编辑帖子回复控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入通用评论检测相关函数 */
require_once(Core_Extend::includeFile('function/Comment_Extend'));

class SubmitreplyController extends GlobalchildController{

	public function index(){
		$nId=intval(G::getGpc('editcid'));

		if($GLOBALS['___login___']===false){
			$this->E(Dyhb::L('你没有登录无法编辑回帖','Controller/Grouptopic'));
		}

		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定编辑回帖的ID','Controller/Grouptopic'));
		}

		$oGrouptopiccomment=GrouptopiccommentModel::F('grouptopiccomment_id=?',$nId)->getOne();
		if(empty($oGrouptopiccomment['grouptopiccomment_id'])){
			$this->E(Dyhb::L('你编辑的回帖不存在','Controller/Grouptopic'));
		}

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$oGrouptopiccomment['grouptopic_id'])->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你要编辑的回帖的主题不存在','Controller/Grouptopic'));
		}

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGrouptopic['group_id'],true);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		if(!Groupadmin_Extend::checkCommentRbac($oGrouptopic->group,$oGrouptopiccomment)){
			$this->E(Dyhb::L('你没有权限编辑回帖','Controller/Grouptopic'));
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

		$sCommentContent=trim($_POST['grouptopiccomment_message']);

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

		// 保存回复数据
		$oGrouptopiccomment->grouptopiccomment_content=$sCommentContent;
		$oGrouptopiccomment->grouptopiccomment_title=trim($_POST['grouptopiccomment_title']);
		$oGrouptopiccomment->setAutofill(false);
		$oGrouptopiccomment->save(0,'update');

		if($oGrouptopiccomment->isError()){
			$this->E($oGrouptopiccomment->getErrorMessage());
		}

		// 发送提醒
		if($GLOBALS['___login___']['user_id']!=$oGrouptopiccomment['user_id']){
			$sGrouptopiccommentmessage=G::subString(strip_tags($oGrouptopiccomment['grouptopiccomment_content']),0,100);
			
			$sNoticetemplate='<div class="notice_editgrouptopiccomment"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('编辑了你的回帖','Controller/Grouptopic').'</span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{content_message}</span></div>&nbsp;'.Dyhb::L('如果你对该操作有任何疑问，可以联系相关人员咨询','Controller/Grouptopic').'</div><div class="notice_action"><a href="{@grouptopiccomment_link}">'.Dyhb::L('查看','Controller/Grouptopic').'</a></div></div>';

			$arrNoticedata=array(
				'@space_link'=>'group://space@?id='.$GLOBALS['___login___']['user_id'],
				'user_name'=>$GLOBALS['___login___']['user_name'],
				'@grouptopiccomment_link'=>'group://grouptopic/view?id='.$oGrouptopiccomment['grouptopic_id'].'&isolation_commentid='.$oGrouptopiccomment['grouptopiccomment_id'],
				'content_message'=>G::subString(strip_tags($sGrouptopiccommentmessage),0,100),
			);

			try{
				Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$oGrouptopiccomment['user_id'],'editgrouptopiccomment',$oGrouptopiccomment['grouptopiccomment_id']);
			}catch(Exception $e){
				$this->E($e->getMessage());
			}
		}

		// 发送评论@提醒
		if($arrParsecontent['atuserids']){
			foreach($arrParsecontent['atuserids'] as $nAtuserid){
				if($nAtuserid!=$GLOBALS['___login___']['user_id']){
					$sGrouptopiccommentmessage=G::subString(strip_tags($oGrouptopiccomment['grouptopiccomment_content']),0,100);
					
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

		$nTotalComment=GrouptopiccommentModel::F('grouptopic_id=?',$oGrouptopic->grouptopic_id)->getCounts();
		$nPage=ceil($nTotalComment/$GLOBALS['_cache_']['group_option']['grouptopic_listcommentnum']);
		
		$sUrl=Dyhb::U('group://topic@?id='.$oGrouptopic->grouptopic_id.($nPage>1?'&page='.$nPage:'').'&extra=new'.$oGrouptopiccomment->grouptopiccomment_id).'#grouptopiccomment-'.($oGrouptopiccomment->grouptopiccomment_id);

		$this->A(array('url'=>$sUrl),Dyhb::L('编辑回帖成功','Controller/Grouptopic'),1);
	}

}
