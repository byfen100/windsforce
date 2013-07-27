<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Group_Extend{

	public static function getGroupIcon($oGroup,$bAdmin=false){
		if(!is_object($oGroup)){
			if(Core_Extend::isPostInt($oGroup)){
				$oGroup=GroupModel::F('group_id=?',$oGroup)->getOne();
			}elseif(is_string($oGroup)){
				$oGroup=GroupModel::F('group_name=?',$oGroup)->getOne();
			}
		}

		if(empty($oGroup['group_id'])){
			return '';
		}
		
		if(!empty($oGroup['group_icon'])){
			return __ROOT__.'/data/upload/app/group/icon/'.$oGroup['group_icon'];
		}else{
			if($bAdmin===false){
				if($oGroup->group_totaltodaynum>0){
					if($oGroup->group_isopen=='1'){
						return Appt::path('group_icon.gif','group');
					}else{
						return Appt::path('group_icon_lock.gif','group');
					}
				}else{
					if($oGroup->group_isopen=='1'){
						return Appt::path('group_icon_old.gif','group');
					}else{
						return Appt::path('group_icon_oldlock.gif','group');
					}
				}
			}else{
				return __ROOT__.'/app/group/Theme/Default/Public/Images/group_icon.gif';
			}
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

	public static function chearGroupuserrole($nUserid){
		// 清理小组长
		$arrGroupusers=GroupuserModel::F('groupuser_isadmin=2 AND user_id=?',$nUserid)->getAll();
		if(!is_array($arrGroupusers)){
			Dyhb::instance('RoleModel')->delGroupUser(2,array($nUserid));
		}

		// 清理小组管理员
		$arrGroupusers=GroupuserModel::F('groupuser_isadmin=1 AND user_id=?',$nUserid)->getAll();
		if(!is_array($arrGroupusers)){
			Dyhb::instance('RoleModel')->delGroupUser(3,array($nUserid));
		}
	}

	static public function clearCookie(){
		Dyhb::cookie('group_grouptopicside',null,-1);
		Dyhb::cookie('group_grouptopicstyle',null,-1);
		Dyhb::cookie('group_homepagestyle',null,-1);
	}

}
