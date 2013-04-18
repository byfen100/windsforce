<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Group_Extend{

	public static function getGroupIcon($sImgname){
		if(!empty($sImgname)){
			return __ROOT__.'/data/upload/app/group/icon/'.$sImgname;
		}else{
			return __ROOT__.'/app/group/Static/Images/group_icon.gif';
		}
	}

	public static function getGroupHeaderbg($sImgname){
		if(!empty($sImgname)){
			if(Core_Extend::isPostInt($sImgname)){
				return __ROOT__.'/app/group/Static/Images/groupbg/'.$sImgname.'.jpg';
			}else{
				return __ROOT__.'/data/upload/app/group/icon/'.$sImgname;
			}
		}else{
			return __ROOT__.'/app/group/Static/Images/groupbg/1.jpg';
		}
	}

	public static function getIconName($sFilename){
		$nId=intval(G::getGpc('id'));
		return Upload_Extend::getIconName('group',$nId).'.'.G::getExtName($sFilename,2);
	}

	public static function getGroupurl($arrGroup,$sMore=''){
		if(is_int($arrGroup)){
			$arrGroup=GroupModel::F('group_id=?',$arrGroup)->getOne();
		}
		
		return Dyhb::U('group://gid@?id='.(!empty($arrGroup['group_name'])?$arrGroup['group_name']:$arrGroup['group_id']).$sMore);
	}

}
