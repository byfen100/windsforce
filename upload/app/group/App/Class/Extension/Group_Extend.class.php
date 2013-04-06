<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Group_Extend{

	public static function getGroupIcon($sImgname){
		if(!empty($sImgname)){
			$sImgname=__ROOT__.'/data/upload/app/group/icon/'.$sImgname;
			return $sImgname;
		}else{
			return __ROOT__.'/app/group/Static/Images/group_icon.gif';
		}
	}

	public static function getIconName($sFilename){
		$nId=intval(G::getGpc('id'));
		return Upload_Extend::getIconName('group',$nId).'.'.G::getExtName($sFilename,2);
	}

	public static function getGroupurl($arrGroup,$sMore=''){
		return Dyhb::U('group://gid@?id='.(!empty($arrGroup['group_name'])?$arrGroup['group_name']:$arrGroup['group_id']).$sMore);
	}
	
	public static function getGroupuser($nGroupid){
		if($GLOBALS['___login___']===false){
			$nGroupuser=0;
		}else{
			$nGroupuser=GroupModel::isGroupuser($nGroupid,$GLOBALS['___login___']['user_id']);
		}

		return $nGroupuser;
	}

	public static function getGrouphottopic($nNum=0,$nDate=0){
		// 热门帖子时间
		if($nDate==0){
			$nDate=$GLOBALS['_cache_']['group_option']['group_hottopic_date'];
			if($nDate<3600){
				$nDate=3600;
			}
		}

		// 热门帖子数量
		if($nNum==0){
			$nNum=$GLOBALS['_cache_']['group_option']['group_hottopic_num'];
			if($nNum<1){
				$nNum=1;
			}
		}
		
		$arrGrouphottopics=GrouptopicModel::F('create_dateline>? AND grouptopic_status=?',CURRENT_TIMESTAMP-$nDate,1)->order('grouptopic_comments DESC')->top($nNum)->get();

		return $arrGrouphottopics;
	}

	public static function getGroupthumbtopic($nNum=0){
		// 首页幻灯片帖子数量
		if($nNum==0){
			$nNum=$GLOBALS['_cache_']['group_option']['group_thumbtopic_num'];
			if($nNum<1){
				$nNum=1;
			}
		}

		$arrGroupthumbtopics=GrouptopicModel::F('grouptopic_status=? AND grouptopic_thumb>0',1)->order('create_dateline DESC')->top($nNum)->get();

		return $arrGroupthumbtopics;
	}

	public static function getGrouphotag($nNum=0,$nDate=0){
		// 热门标签时间
		if($nDate==0){
			$nDate=$GLOBALS['_cache_']['group_option']['group_hottag_date'];
			if($nDate<3600){
				$nDate=3600;
			}
		}

		// 热门标签数量
		if($nNum==0){
			$nNum=$GLOBALS['_cache_']['group_option']['group_hottag_num'];
			if($nNum<1){
				$nNum=1;
			}
		}
		
		$arrGrouphottags=GrouptopictagModel::F('create_dateline>?',CURRENT_TIMESTAMP-$nDate)->order('grouptopictag_count DESC')->top($nNum)->get();

		return $arrGrouphottopics;
	}

	static public function grouptopicClose($nClosestatus,$bReturnImg=false){
		if($nClosestatus==1){
			if($bReturnImg===true){
				return ' <img class="grouptopicclose_date" src="'.__APPPUB__.'/Images/locked.gif" border="0" align="absmiddle" title="关闭主题"/> ';
			}else{
				return __APPPUB__.'/Images/locked.gif';
			}
		}

		return '';
	}
	
	static public function grouptopicStick($nStickstatus,$bReturnImg=false){
		if($nStickstatus>0){
			if($bReturnImg===true){
				return ' <img class="grouptopicstick_date" src="'.__APPPUB__.'/Images/grouptopic/sticktopic_'.$nStickstatus.'.gif" border="0" align="absmiddle" title="置顶主题'.$nStickstatus.'"/> ';
			}else{
				return __APPPUB__.'/Images/locked.gif';
			}
		}

		return '';
	}

	static public function grouptopicDigest($nDigeststatus,$bReturnImg=false){
		if($nDigeststatus>0){
			if($bReturnImg===true){
				return ' <img class="grouptopicdigest_date" src="'.__APPPUB__.'/Images/grouptopic/digest_'.$nDigeststatus.'.gif" border="0" align="absmiddle" title="精华主题 '.$nDigeststatus.'"/> ';
			}else{
				return __APPPUB__.'/Images/locked.gif';
			}
		}

		return '';
	}

	static public function grouptopicRecommend($nRecommendstatus,$bReturnImg=false){
		if($nRecommendstatus>0){
			if($bReturnImg===true){
				return ' <img class="grouptopicrecommend_date" src="'.__APPPUB__.'/Images/grouptopic/recommend.gif" border="0" align="absmiddle" title="推荐主题"/> ';
			}else{
				return __APPPUB__.'/Images/locked.gif';
			}
		}

		return '';
	}

	static public function grouptopiclistIcon($oGrouptopic,$bReturnImg=false){
		$sGroupurl=Dyhb::U('group://topic@?id='.$oGrouptopic['grouptopic_id']);

		$sTitle='新窗口打开';
		$sIcon=__APPPUB__.'/Images/folder_common.gif';
		
		if($oGrouptopic->grouptopic_comments>0){
			$arrLatestComment=@unserialize($oGrouptopic->grouptopic_latestcomment);
			
			if(CURRENT_TIMESTAMP-$arrLatestComment['commenttime']<=86400){
				$sIcon=__APPPUB__.'/Images/folder_new.gif';
				$sTitle='有新回复 - '.$sTitle;
			}
		}
		
		if($oGrouptopic['grouptopic_sticktopic']>0){
			$sIcon=__APPPUB__.'/Images/grouptopic/sticktopic_'.$oGrouptopic['grouptopic_sticktopic'].'.gif';
			$sTitle='置顶主题 '.$oGrouptopic['grouptopic_sticktopic'].' - '.$sTitle;
		}

		if($oGrouptopic['grouptopic_isclose']==1){
			$sIcon=__APPPUB__.'/Images/locked.gif';
			$sTitle='关闭的主题 - '.$sTitle;
		}

		return '<a href="'.$sGroupurl.'" title="'.$sTitle.'" target="_blank"><img src="'.$sIcon.'" /></a>';
	}

	static public function grouptopicColor($sColor){
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

}
