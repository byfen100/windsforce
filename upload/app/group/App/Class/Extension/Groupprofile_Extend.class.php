<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组个人相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Groupprofile_Extend{

	static public function totalTopic($nUserid,$bDigesttopic=false){
		return GrouptopicModel::F('user_id=? AND grouptopic_status=1 AND grouptopic_isaudit=1'.($bDigesttopic===true?' AND grouptopic_addtodigest>0':''),$nUserid)->getCounts();
	}

	static public function totalComment($nUserid){
		return GrouptopiccommentModel::F('user_id=? AND grouptopiccomment_status=1 AND grouptopiccomment_auditpass=1 AND grouptopiccomment_ishide=0',$nUserid)->getCounts();
	}

}
