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

}
