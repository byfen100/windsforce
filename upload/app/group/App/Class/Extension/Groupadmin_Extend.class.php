<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组管理相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Groupadmin_Extend{
	
	public static function getGroupuser($nGroupid){
		if($GLOBALS['___login___']===false){
			$nGroupuser=0;
		}else{
			$nGroupuser=GroupModel::isGroupuser($nGroupid,$GLOBALS['___login___']['user_id']);
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

	static public function checkTopicadminRbac($oGroup,$arrType=array('group@grouptopicadmin@deletetopic','group@grouptopicadmin@closetopic','group@grouptopicadmin@sticktopic','group@grouptopicadmin@digesttopic','group@grouptopicadmin@recommendtopic','group@grouptopicadmin@hidetopic','group@grouptopicadmin@categorytopic','group@grouptopicadmin@tagtopic','group@grouptopicadmin@colortopic')){
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

}
