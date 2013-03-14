<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   通用评论检测相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入home应用配置值 */
if(!Dyhb::classExists('HomeoptionModel')){
	require_once(WINDSFORCE_PATH.'/app/home/App/Class/Model/HomeoptionModel.class.php');
}

/** 载入home应用配置信息 */
if(!isset($GLOBALS['_cache_']['home_option'])){
	Core_Extend::loadCache('home_option');
}

class Comment_Extend{

	static public function banIp($sOnlineip=null){
		$arrOptions=$GLOBALS['_cache_']['home_option'];
		
		$sCommentBanIp=trim($arrOptions['comment_ban_ip']);
		if(is_null($sOnlineip)){
			$sOnlineip=G::getIp();
		}

		if($arrOptions['comment_banip_enable']==1 && $sCommentBanIp){
			$sCommentBanIp=str_replace('，',',',$sCommentBanIp);
			$arrCommentBanIp=Dyhb::normalize(explode(',', $sCommentBanIp));

			if(is_array($arrCommentBanIp) && count($arrCommentBanIp)){
				foreach($arrCommentBanIp as $sValueCommentBanIp){
					$sValueCommentBanIp=str_replace('\*','.*',preg_quote($sValueCommentBanIp,"/"));
					if(preg_match("/^{$sValueCommentBanIp}/",$sOnlineip)){
						return false;
					}
				}
			}
		}

		return true;
	}

	static public function commentName($sCommentName){
		$arrNamekeys=array("\\",'&',' ',"'",'"','/','*',',','<','>',"\r","\t","\n",'#','$','(',')','%','@','+','?',';','^');
		
		foreach($arrNamekeys as $sNamekey){
			if(strpos($sCommentName,$sNamekey)!==false){
				return false;
			}
		}

		return true;
	}

	static public function commentMinLen($sCommentContent){
		$arrOptions=$GLOBALS['_cache_']['home_option'];
		
		$nCommentMinLen=intval($arrOptions['comment_min_len']);
		if($nCommentMinLen && strlen($sCommentContent)<$nCommentMinLen){
			return false;
		}

		return true;
	}

	static public function commentMaxLen($sCommentContent){
		$arrOptions=$GLOBALS['_cache_']['home_option'];
		
		$nCommentMaxLen=intval($arrOptions['comment_max_len']);
		if($nCommentMaxLen && strlen($sCommentContent)>$nCommentMaxLen){
			return false;
		}

		return true;
	}

	static public function commentSpamUrl($sCommentContent){
		$arrOptions=$GLOBALS['_cache_']['home_option'];
		$bDisallowedSpamWordToDatabase=$arrOptions['disallowed_spam_word_to_database']?true:false;
		
		if($arrOptions['comment_spam_enable']){
			$nCommentSpamUrlNum=intval($arrOptions['comment_spam_url_num']);
			if($nCommentSpamUrlNum){
				if(substr_count($sCommentContent,'http://')>=$nCommentSpamUrlNum){
					if($bDisallowedSpamWordToDatabase){
						return false;
					}else{
						return 0;
					}
				}
			}
		}

		return true;
	}

	static public function commentSpamWords($sCommentContent){
		$arrOptions=$GLOBALS['_cache_']['home_option'];
		$bDisallowedSpamWordToDatabase=$arrOptions['disallowed_spam_word_to_database']?true:false;
		
		if($arrOptions['comment_spam_enable']){
			$sSpamWords=trim($arrOptions['comment_spam_words']);
			if($sSpamWords){
				$sSpamWords=str_replace('，',',',$sSpamWords);
				$arrSpamWords=Dyhb::normalize(explode(',',$sSpamWords));
				if(is_array($arrSpamWords) && count($arrSpamWords)){
					foreach($arrSpamWords as $sValueSpamWords){
						if($sValueSpamWords){
							if(preg_match("/".preg_quote($sValueSpamWords,'/')."/i",$sCommentContent)){
								if($bDisallowedSpamWordToDatabase){
									return array(false,$sValueSpamWords);
								}else{
									return 0;
									$oHomefreshcomment->homefreshcomment_status=0;
								}
								break;
							}
						}
					}
				}
			}
		}

		return true;
	}

	static public function commentSpamContentsize($sCommentContent){
		$arrOptions=$GLOBALS['_cache_']['home_option'];
		$bDisallowedSpamWordToDatabase=$arrOptions['disallowed_spam_word_to_database']?true:false;
		
		if($arrOptions['comment_spam_enable']){
			$nCommentSpamContentSize=intval($arrOptions['comment_spam_content_size']);
			if($nCommentSpamContentSize){
				if(strlen($sCommentContent)>=$nCommentSpamContentSize){
					if($bDisallowedSpamWordToDatabase){
						return false;
					}else{
						return 0;
					}
				}
			}

			return true;
		}
	}

	static public function commentSpamPostSpace($nLastPostTime){
		$arrOptions=$GLOBALS['_cache_']['home_option'];
		$nLastPostTime=intval($nLastPostTime);
		$nCommentPostSpace=intval($arrOptions['comment_post_space']);
		
		if(CURRENT_TIMESTAMP-$nLastPostTime<=$nCommentPostSpace){
			return false;
		}

		return true;
	}

	static public function commentDisallowedallenglishword($sCommentContent){
		$arrOptions=$GLOBALS['_cache_']['home_option'];
		$bDisallowedSpamWordToDatabase=$arrOptions['disallowed_spam_word_to_database']?true:false;

		if($arrOptions['disallowed_all_english_word']){
			$sPattern='/[一-龥]/u';
			if(!preg_match_all($sPattern,$sCommentContent,$arrMatch)){
				if($bDisallowedSpamWordToDatabase){
					return false;
				}else{
					return 0;
				}
			}
		}

		return true;
	}

	static public function sendCookie($sCommentname,$sCommenturl,$sCommentemail){
		Dyhb::cookie('the_comment_name',$sCommentname,86400);
		Dyhb::cookie('the_comment_url',$sCommenturl,86400);
		Dyhb::cookie('the_comment_email',$sCommentemail,86400);
	}

	static public function addFeed($sTitle,$sType,$sCommentLink,$sCommentTitle,$sCommentMessage){
		$sFeedtemplate='<div class="feed_'.$sType.'"><span class="feed_title">'.$sTitle.'&nbsp;<a href="{@commentlink}">{commenttitle}</a></span><div class="feed_content"><div class="feed_quote"><span class="feed_quoteinfo">{commentmessage}</span></div></div><div class="feed_action"><a href="{@commentlink}">'.Dyhb::L('回复','__COMMON_LANG__@Function/Comment_Extend').'</a></div></div>';

		$arrFeeddata=array(
			'@commentlink'=>$sCommentLink,
			'commenttitle'=>G::subString($sCommentTitle,0,30),
			'commentmessage'=>G::subString($sCommentMessage,0,100),
		);

		Core_Extend::addFeed($sFeedtemplate,$arrFeeddata);
	}

	static public function addNotice($sTitle,$sType,$sCommentLink,$sCommentTitle,$sCommentMessage,$nUserid,$sNoticeType,$nFromId){
		$sNoticetemplate='<div class="notice_'.$sType.'"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.$sTitle.'&nbsp;<a href="{@commentlink}">{commenttitle}</a></span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{commentmessage}</span></div></div><div class="notice_action"><a href="{@commentlink}">'.Dyhb::L('查看','__COMMON_LANG__@Function/Comment_Extend').'</a></div></div>';

		$arrNoticedata=array(
			'@space_link'=>'home://space@?id='.$GLOBALS['___login___']['user_id'],
			'user_name'=>$GLOBALS['___login___']['user_name'],
			'@commentlink'=>$sCommentLink,
			'commenttitle'=>G::subString($sCommentTitle,0,30),
			'commentmessage'=>G::subString($sCommentMessage,0,100),
		);

		Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$nUserid,$sNoticeType,$nFromId);
	}

	static public function commentSendmail($sCommentName,$sCommentContent,$sCommentLink,$sCommentEmail,$sCommentUrl,$nCommentParentIsreplymail,$sCommentParentName,$sCommentParentContent,$sCommentParentLink,$sCommentParentEmail,$sCommentParentUrl){
		$bSendMail=$bSendMailAdmin=$bSendMailAuthor=false;

		// 是否发送邮件
		$sAdminEmail=$GLOBALS['_option_']['admin_email'];
		if($GLOBALS['_cache_']['home_option']['comment_mail_to_admin']==1 && !empty($sAdminEmail)){
			$bSendMailAdmin=true;
		}

		if($GLOBALS['_cache_']['home_option']['comment_mail_to_author']==1 && !empty($nCommentParentid)){
			$bSendMailAuthor=true;
		}

		if($bSendMailAdmin || $bSendMailAuthor){
			$bSendMail=true;
		}

		if($bSendMail===false){
			return;
		}

		// 开始发送邮件
		$oMail=Dyhb::instance('MailModel');
		$oMailConnect=$oMail->getMailConnect();
		if($bSendMailAdmin===true){
			$sEmailSubject=self::getEmailToAdminsubject($sCommentName);
			$sEmailMessage=self::getEmailToAdminmessage($oMailConnect,$sCommentName,$sCommentContent,$sCommentLink,$sCommentEmail,$sCommentUrl);
			self::sendAEmail($oMailConnect,$sAdminEmail,$sEmailSubject,$sEmailMessage);
		}

		if($bSendMailAuthor===true){
			if($nCommentParentIsreplymail==1 && !empty($sCommentParentEmail)){
				$sEmailSubject=self::getEmailToAuthorsubject($sCommentParentName);
				$sEmailMessage=self::getEmailToAuthormessage($oMailConnect,$sCommentParentName,$sCommentParentContent,$sCommentParentLink,$sCommentParentEmail,$sCommentParentUrl,$sCommentContent);
				self::sendAEmail($oMailConnect,$sCommentParentEmail,$sEmailSubject,$sEmailMessage);
			}
		}

		return;
	}

	static public function getEmailToAdminsubject($sCommentName){
		return Dyhb::L('你的朋友【%s】在您的网站（%s）留言了！','__COMMON_LANG__@Function/Comment_Extend',null,$sCommentName,$GLOBALS['_option_']['site_name']);
	}

	static public function getEmailToAdminmessage($oMailConnect,$sCommentName,$sCommentContent,$sCommentLink,$sCommentEmail,$sCommentUrl){
		$sLine=self::getMailline($oMailConnect);

		$sMessage=self::getEmailToAdminsubject($sCommentName)."{$sLine}";
		$sMessage.="-----------------------------------------------------{$sLine}";
		$sMessage.=$sCommentContent."{$sLine}{$sLine}";
		$sMessage.=Dyhb::L('请进入下面超链接查看留言','__COMMON_LANG__@Function/Comment_Extend').":{$sLine}";
		$sMessage.=$sCommentLink."{$sLine}";
		$sMessage.="-----------------------------------------------------{$sLine}";
		$sMessage.=Dyhb::L('名字','__COMMON_LANG__@Function/Comment_Extend').':'.$sCommentName."{$sLine}";

		if(!empty($sCommentEmail)){
			$sMessage.='E-mail:'.$sCommentEmail."{$sLine}";
		}

		if(!empty($sCommentUrl)){
			$sMessage.=Dyhb::L('主页','__COMMON_LANG__@Function/Comment_Extend').':'.$sCommentUrl."{$sLine}";
		}

		$sMessage.=self::getSiteinf($sLine);

		return $sMessage;
	}

	static function getEmailToAuthorsubject($sCommentName){
		return Dyhb::L("我的朋友：【%s】您在网站（%s）发表的评论被回复了！",'__COMMON_LANG__@Function/Comment_Extend',null,$sCommentName,$GLOBALS['_option_']['site_name']);
	}

	protected function getEmailToAuthormessage($oMailSend,$sCommentName,$sCommentContent,$sCommentLink,$sCommentEmail,$sCommentUrl,$sNewCommentContent){
		$sLine=self::getMailline($oMailSend);

		$sMessage=self::getEmailToAuthorsubject($sCommentName).$sLine;
		$sMessage.="-----------------------------------------------------{$sLine}";
		$sMessage.=Dyhb::L('您的评论','__COMMON_LANG__@Function/Comment_Extend').":{$sLine}";
		$sMessage.=$sCommentContent."{$sLine}";
		$sMessage.="-----------------------------------------------------{$sLine}";
		$sMessage.="【".$sCommentName."】".Dyhb::L('回复说','__COMMON_LANG__@Function/Comment_Extend').":{$sLine}";
		$sMessage.=$sNewCommentContent."{$sLine}{$sLine}";
		$sMessage.=Dyhb::L('请进入下面链接查看评论','__COMMON_LANG__@Function/Comment_Extend').":{$sLine}";
		$sMessage.=$sCommentLink."{$sLine}";
		$sMessage.="-----------------------------------------------------{$sLine}";
		$sMessage.=Dyhb::L('名字','__COMMON_LANG__@Function/Comment_Extend').':'.$sCommentName."{$sLine}";

		if(!empty($sCommentEmail)){
			$sMessage.='E-mail:'.$sCommentEmail."{$sLine}";
		}

		if(!empty($sCommentUrl)){
			$sMessage.=Dyhb::L('主页','__COMMON_LANG__@Function/Comment_Extend').':'.$sCommentUrl."{$sLine}";
		}

		$sMessage.=self::getSiteinf($sLine);

		return $sMessage;
	}

	static public function getMailline($oMailConnect){
		return $oMailConnect->getIsHtml()===true?'<br/>':"\r\n";
	}

	static public function getSiteinfo($sLine){
		$sMessage="-----------------------------------------------------{$sLine}";
		$sMessage.=Dyhb::L('消息来源','__COMMON_LANG__@Function/Comment_Extend').':'.$GLOBALS['_option_']['site_name']."{$sLine}";
		$sMessage.=Dyhb::L('站点网址','__COMMON_LANG__@Function/Comment_Extend').':'.$GLOBALS['_option_']['site_url']."{$sLine}";
		$sMessage.="-----------------------------------------------------{$sLine}";
		$sMessage.=Dyhb::L('程序支持','__COMMON_LANG__@Function/Comment_Extend').':'.$GLOBALS['_option_']['windsforce_program_name']." " .WINDSFORCE_SERVER_VERSION. " Release " .WINDSFORCE_SERVER_RELEASE."{$sLine}";
		$sMessage.=Dyhb::L('产品官网','__COMMON_LANG__@Function/Comment_Extend').':'.$GLOBALS['_option_']['windsforce_program_url']."{$sLine}";
	}

	static public function sendAEmail($oMailConnect,$sEmailTo,$sEmailSubject,$sEmailMessage){
		$oMail=Dyhb::instance('MailModel');
		$oMail->sendAEmail($oMailConnect,$sEmailTo,$sEmailSubject,$sEmailMessage,'home');
		if($oMail->isError()){
			Dyhb::E($oMail->getErrorMessage());
		}
	}

}
