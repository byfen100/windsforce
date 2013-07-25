<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动管理相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Eventadmin_Extend{

	static public function checkEvent($oEvent){
		if(Core_Extend::isAdmin()){
			return true;
		}
		
		if(!is_object($oEvent)){
			$oEvent=EventModel::F('event_id=? AND event_status=1',$oEvent)->getOne();
		}

		if(empty($oEvent['event_id'])){
			return false;
		}

		if($oEvent['user_id'] && $oEvent['user_id']==$GLOBALS['___login___']['user_id']){
			return true;
		}

		return false;
	}

}
