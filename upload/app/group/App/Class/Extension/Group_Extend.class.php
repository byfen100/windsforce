<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   群组相关函数($)*/

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

	public static function getGroupurl($arrGroup){
		return Dyhb::U('group://gid@?id='.(!empty($arrGroup['group_name'])?$arrGroup['group_name']:$arrGroup['group_id']));
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

}
