<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组管理相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Groupadmin_Extend{
	
	public static function getGroupuser($nGroupid,$nUserid=0){
		if($nUserid>0){
			$nGroupuser=GroupModel::isGroupuser($nGroupid,$nUserid);
		}else{
			if($GLOBALS['___login___']===false){
				$nGroupuser=0;
			}else{
				$nGroupuser=GroupModel::isGroupuser($nGroupid,$GLOBALS['___login___']['user_id']);
			}
		}

		return $nGroupuser;
	}

	static public function getGroupUserrole($nGroupid){
		/* 4 超级管理员 -1 游客 -2 非成员 0 会员 1 小组管理员 2 组长 */
		if($GLOBALS['___login___']===false){
			return -1;
		}
		
		if(Core_Extend::isAdmin()){
			return 4;
		}

		$oTrygroupuser=GroupuserModel::F('user_id=? AND group_id=?',$GLOBALS['___login___']['user_id'],$nGroupid)->getOne();
		
		if(empty($oTrygroupuser['user_id'])){
			return -2;
		}else{
			return $oTrygroupuser['groupuser_isadmin'];
		}
	}

	static public function checkTopicedit($oGrouptopic){
		if(!Core_Extend::checkRbac('group@grouptopic@edit')){
			return false;
		}

		$nGroupuserrole=Groupadmin_Extend::getGroupUserrole($oGrouptopic->group_id);
		
		$bAllowedEdittopic=false;
		if(in_array($nGroupuserrole,array(1,2,4))){
			$bAllowedEdittopic=true;
		}

		if($GLOBALS['___login___']!==false && $GLOBALS['___login___']['user_id']==$oGrouptopic['user_id']){
			$bAllowedEdittopic=true;
		}

		return $bAllowedEdittopic;
	}

	static public function checkTopicmove(){
		if(Core_Extend::isAdmin()){
			return true;
		}
		
		if(!Core_Extend::checkRbac('group@grouptopicadmin@movetopic')){
			return false;
		}

		// 再次限制，仅管理员（前提是已经授权）和后台超级管理员可以执行
		$oUserrole=UserroleModel::F('user_id=? AND role_id=1',$GLOBALS['___login___']['user_id'])->getOne();
		if(!empty($oUserrole['user_id'])){
			return true;
		}

		return false;
	}

	static public function checkCommentRbac($oGroup,$oComment){
		if(!Core_Extend::checkRbac('group@grouptopic@editcommenttopic_dialog')){
			return false;
		}

		$nGroupuserrole=Groupadmin_Extend::getGroupUserrole($oGroup->group_id);
		
		$bAllowedEditcomment=false;
		if(in_array($nGroupuserrole,array(1,2,4))){
			$bAllowedEditcomment=true;
		}

		if(($nGroupuserrole==0 && $oGroup->group_ispost!=1) || ($nGroupuserrole==-2 && $oGroup->group_ispost==2)){
			if($oComment['user_id']==$GLOBALS['___login___']['user_id'] && CURRENT_TIMESTAMP-$oComment['create_dateline']<=$GLOBALS['_cache_']['group_option']['grouptopiccomment_edit_limittime']){
				$bAllowedEditcomment=true;
			}
		}

		return $bAllowedEditcomment;
	}

	static public function checkCommentadminRbac($oGroup,$arrType=array('group@grouptopicadmin@deletecomment','group@grouptopicadmin@hidecomment','group@grouptopicadmin@stickreplycomment','group@grouptopicadmin@auditcomment')){
		if(!Core_Extend::checkRbac($arrType)){
			return false;
		}

		$nGroupuserrole=Groupadmin_Extend::getGroupUserrole($oGroup->group_id);
		
		$bAllowedEditcomment=false;
		if(in_array($nGroupuserrole,array(1,2,4))){
			$bAllowedEditcomment=true;
		}

		return $bAllowedEditcomment;
	}

	static public function checkTopicadminRbac($nGroupid,$arrType=array('group@grouptopicadmin@deletetopic','group@grouptopicadmin@closetopic','group@grouptopicadmin@sticktopic','group@grouptopicadmin@digesttopic','group@grouptopicadmin@recommendtopic','group@grouptopicadmin@hidetopic','group@grouptopicadmin@categorytopic','group@grouptopicadmin@tagtopic','group@grouptopicadmin@colortopic')){
		if(is_object($nGroupid)){
			$nGroupid=$nGroupid->group_id;
		}
		
		if(!Core_Extend::checkRbac($arrType)){
			return false;
		}

		$nGroupuserrole=Groupadmin_Extend::getGroupUserrole($nGroupid);
		
		$bAllowedEditcomment=false;
		if(in_array($nGroupuserrole,array(1,2,4))){
			$bAllowedEditcomment=true;
		}

		return $bAllowedEditcomment;
	}

	static public function checkGroup($oGroup,$bCheckAdd=false,$nUserid=null){
		if(Core_Extend::isAdmin()){
			return true;
		}
		
		if(is_null($nUserid)){
			$nUserid=$GLOBALS['___login___']['user_id'];
		}

		if(!is_object($oGroup)){
			$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$oGroup)->getOne();
			
			if(empty($oGroup['group_id'])){
				Dyhb::E(Dyhb::L('小组不存在或者还在审核中','__APPGROUP_COMMON_LANG__@Function/Groupadmin_Extend'));
			}
		}

		if($oGroup->group_isopen==0){
			if($GLOBALS['___login___']===false){
				G::urlGoTo(Core_Extend::windsforceReferer(),3,Dyhb::L('只有该小组成员才能够访问小组','__APPGROUP_COMMON_LANG__@Function/Groupadmin_Extend').'&nbsp;&nbsp;'.Dyhb::L('首先你需要的登录后才能够继续操作','__APPGROUP_COMMON_LANG__@Function/Groupadmin_Extend'));
			}
			
			$oGroupuser=GroupuserModel::F('user_id=? AND group_id=?',$nUserid,$oGroup['group_id'])->getOne();
			if(empty($oGroupuser['user_id'])){
				Dyhb::E(Dyhb::L('只有该小组成员才能够访问小组','__APPGROUP_COMMON_LANG__@Function/Groupadmin_Extend').'&nbsp;<span id="listgroup_'.$oGroup['group_id'].'" class="commonjoinleave_group"><a href="javascript:void(0);" onclick="joinGroup('.$oGroup['group_id'].',\'listgroup_'.$oGroup['group_id'].'\');">'.Dyhb::L('我要加入','__APPGROUP_COMMON_LANG__@Function/Groupadmin_Extend').'</a></span>');
			}
		}

		if($bCheckAdd===true){
			if($oGroup->group_ispost==0){
				$oGroupuser=GroupuserModel::F('user_id=? AND group_id=?',$GLOBALS['___login___']['user_id'],$oGroup['group_id'])->getOne();
				if(empty($oGroupuser['user_id'])){
					Dyhb::E(Dyhb::L('只有该小组成员才能发帖','__APPGROUP_COMMON_LANG__@Function/Groupadmin_Extend').'&nbsp;<span id="listgroup_'.$oGroup['group_id'].'" class="commonjoinleave_group"><a href="javascript:void(0);" onclick="joinGroup('.$oGroup['group_id'].',\'listgroup_'.$oGroup['group_id'].'\');">'.Dyhb::L('我要加入','__APPGROUP_COMMON_LANG__@Function/Groupadmin_Extend').'</a></span>');
				}
			}elseif($oGroup->group_ispost==1){
				Dyhb::E(Dyhb::L('该小组目前拒绝任何人发帖','__APPGROUP_COMMON_LANG__@Function/Groupadmin_Extend'));
			}
		}

		return true;
	}

}
