<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组帖子相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Grouptopic_Extend{

	static public function grouptopicClose($nClosestatus,$bReturnImg=false){
		if($nClosestatus==1){
			$sImgurl=Appt::path('locked.gif','group');
			
			if($bReturnImg===true){
				return ' <img class="grouptopicclose_date" src="'.$sImgurl.'" border="0" align="absmiddle" title="'.Dyhb::L('关闭主题','__APPGROUP_COMMON_LANG__@Function/Grouptopic_Extend').'"/> ';
			}else{
				return $sImgurl;
			}
		}

		return '';
	}
	
	static public function grouptopicStick($nStickstatus,$bReturnImg=false){
		if($nStickstatus>0){
			if($bReturnImg===true){
				return ' <img class="grouptopicstick_date" src="'.__APPPUB__.'/Images/grouptopic/sticktopic_'.$nStickstatus.'.gif" border="0" align="absmiddle" title="'.($nStickstatus==3?Dyhb::L('全局置顶主题','__APPGROUP_COMMON_LANG__@Function/Grouptopic_Extend'):Dyhb::L('小组置顶主题','__APPGROUP_COMMON_LANG__@Function/Grouptopic_Extend').' '.$nStickstatus).'"/> ';
			}else{
				return __APPPUB__.'/Images/locked.gif';
			}
		}

		return '';
	}

	static public function grouptopicDigest($nDigeststatus,$bReturnImg=false){
		if($nDigeststatus>0){
			if($bReturnImg===true){
				return ' <img class="grouptopicdigest_date" src="'.__APPPUB__.'/Images/grouptopic/digest_'.$nDigeststatus.'.gif" border="0" align="absmiddle" title="'.Dyhb::L('精华主题','__APPGROUP_COMMON_LANG__@Function/Grouptopic_Extend').' '.$nDigeststatus.'"/> ';
			}else{
				return __APPPUB__.'/Images/locked.gif';
			}
		}

		return '';
	}

	static public function grouptopicRecommend($nRecommendstatus,$bReturnImg=false){
		if($nRecommendstatus>0){
			if($bReturnImg===true){
				return ' <img class="grouptopicrecommend_date" src="'.__APPPUB__.'/Images/grouptopic/recommend_'.$nRecommendstatus.'.gif" border="0" align="absmiddle" title="'.($nRecommendstatus==2?Dyhb::L('系统推荐主题','__APPGROUP_COMMON_LANG__@Function/Grouptopic_Extend'):Dyhb::L('组长推荐主题','__APPGROUP_COMMON_LANG__@Function/Grouptopic_Extend')).'"/> ';
			}else{
				return __APPPUB__.'/Images/locked.gif';
			}
		}

		return '';
	}

	static public function grouptopicThumb($nThumbstatus,$bReturnImg=false){
		if($nThumbstatus>0){
			if($bReturnImg===true){
				return ' <img class="grouptopicthumb_date" src="'.__APPPUB__.'/Images/image.gif" border="0" align="absmiddle" title="'.Dyhb::L('缩略图主题','__APPGROUP_COMMON_LANG__@Function/Grouptopic_Extend').'"/> ';
			}else{
				return __APPPUB__.'/Images/image.gif';
			}
		}

		return '';
	}

	static public function grouptopicHot($nCommentnum,$nViewnum,$bReturnImg=false){
		$nHot=0;
		
		if($nCommentnum>=$GLOBALS['_cache_']['group_option']['group_hottopic3_comments'] && $nViewnum>=$GLOBALS['_cache_']['group_option']['group_hottopic3_views']){
			$nHot=3;
		}elseif($nCommentnum>=$GLOBALS['_cache_']['group_option']['group_hottopic2_comments'] && $nViewnum>=$GLOBALS['_cache_']['group_option']['group_hottopic2_views']){
			$nHot=2;
		}elseif($nCommentnum>=$GLOBALS['_cache_']['group_option']['group_hottopic1_comments'] && $nViewnum>=$GLOBALS['_cache_']['group_option']['group_hottopic1_views']){
			$nHot=1;
		}
		
		if($nHot>0){
			if($bReturnImg===true){
				return ' <img class="grouptopicthumb_date" src="'.__APPPUB__.'/Images/hot_'.$nHot.'.gif'.'" border="0" align="absmiddle" title="'.Dyhb::L('热门主题','__APPGROUP_COMMON_LANG__@Function/Grouptopic_Extend').$nHot.'"/> ';
			}else{
				return __APPPUB__.'/Images/hot_'.$nHot.'.gif';
			}
		}

		return '';
	}

	static public function grouptopicHighlight($sColor,$bReturnImg=false){
		if(!$sColor){
			return '';
		}

		$arrColor=@unserialize($sColor);
		if($arrColor){
			if($bReturnImg===true){
				return ' <img class="grouptopichighlight_date" src="'.__APPPUB__.'/Images/highlight.gif" border="0" align="absmiddle" title="'.Dyhb::L('高亮主题','__APPGROUP_COMMON_LANG__@Function/Grouptopic_Extend').'"/> ';
			}else{
				return __APPPUB__.'/Images/highlight.gif';
			}
		}else{
			return '';
		}
	}

	static public function grouptopiclistIcon($oGrouptopic,$bReturnImg=false){
		$sGroupurl=Dyhb::U('group://topic@?id='.$oGrouptopic['grouptopic_id']);

		$sTitle=Dyhb::L('新窗口打开','__APPGROUP_COMMON_LANG__@Function/Grouptopic_Extend');
		$sIcon=__APPPUB__.'/Images/folder_common.gif';
		
		if($oGrouptopic->grouptopic_comments>0){
			$arrLatestComment=@unserialize($oGrouptopic->grouptopic_latestcomment);
			
			if(CURRENT_TIMESTAMP-$arrLatestComment['commenttime']<=86400){
				$sIcon=__APPPUB__.'/Images/folder_new.gif';
				$sTitle=Dyhb::L('有新回复','__APPGROUP_COMMON_LANG__@Function/Grouptopic_Extend').' - '.$sTitle;
			}
		}
		
		if($oGrouptopic['grouptopic_sticktopic']>0){
			$sIcon=__APPPUB__.'/Images/grouptopic/sticktopic_'.$oGrouptopic['grouptopic_sticktopic'].'.gif';
			$sTitle=($oGrouptopic['grouptopic_sticktopic']==3?Dyhb::L('全局置顶主题','__APPGROUP_COMMON_LANG__@Function/Grouptopic_Extend'):Dyhb::L('小组置顶主题','__APPGROUP_COMMON_LANG__@Function/Grouptopic_Extend').' '.$oGrouptopic['grouptopic_sticktopic']).' - '.$sTitle;
		}

		if($oGrouptopic['grouptopic_isclose']==1){
			$sIcon=__APPPUB__.'/Images/locked.gif';
			$sTitle=Dyhb::L('关闭的主题','__APPGROUP_COMMON_LANG__@Function/Grouptopic_Extend').' - '.$sTitle;
		}

		return '<a href="'.$sGroupurl.'" title="'.$sTitle.'" target="_blank"><img src="'.$sIcon.'" /></a>';
	}

	static public function grouptopicColor($sColor){
		if(!$sColor){
			return '';
		}

		$arrColor=@unserialize($sColor);
		if($arrColor){
			$sReturn='';

			if(!empty($arrColor[0])){
				$sReturn.='color:'.$arrColor[0].';';
			}

			if(!empty($arrColor[1][1])){
				$sReturn.="font-weight: bold;";
			}

			if(!empty($arrColor[1][2])){
				$sReturn.="font-style: italic;";
			}

			if(!empty($arrColor[1][3])){
				$sReturn.="text-decoration: underline;";
			}

			if(!empty($arrColor[2])){
				$sReturn.='background-color:'.$arrColor[2].';';
			}

			return $sReturn;
		}
	}

	static public function getTopicpages($oGrouptopic){
		// 读取帖子的评论数量
		$arrWhere=array();
		$nEverynum=$GLOBALS['_cache_']['group_option']['grouptopic_listcommentnum'];

		$arrWhere['grouptopiccomment_status']=1;
		$arrWhere['grouptopic_id']=$oGrouptopic->grouptopic_id;

		if(!Groupadmin_Extend::checkCommentadminRbac($oGrouptopic->group,array('group@grouptopicadmin@auditcomment'))){
			$arrWhere['grouptopiccomment_auditpass']=1;
		}

		$nTotalComment=GrouptopiccommentModel::F()->where($arrWhere)->all()->getCounts();
		
		$sPagelinks='';
		if($nTotalComment>$nEverynum){
			$nPages=ceil($nTotalComment/$nEverynum);
			for($nI=2;$nI<=6 && $nI<=$nPages;$nI++){
				$sPagelinks.="<a href=\"".Dyhb::U('group://topic@?id='.$oGrouptopic['grouptopic_id'].'&page='.$nI)."\">{$nI}</a>";
			}

			if($nPages>6){
				$sPagelinks.="<span class=\"disabled\">..</span><a href=\"".Dyhb::U('group://topic@?id='.$oGrouptopic['grouptopic_id'].'&page='.$nPages)."\">{$nPages}</a>";
			}

			$sPagelinks='&nbsp;<span class="disabled">...</span>'.$sPagelinks;
		}

		return $sPagelinks;
	}

}
