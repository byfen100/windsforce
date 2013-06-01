<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   网站公告缓存($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UpdateCacheAnnouncement{

	public static function cache(){
		$arrData=array();

		$arrAnnouncements=AnnouncementModel::F()->order('announcement_sort ASC,create_dateline DESC')->limit(0,10)->getAll();
		if(is_array($arrAnnouncements)){
			foreach($arrAnnouncements as $oAnnouncement){
				$arrData[]=array(
					'announcement_title'=>$oAnnouncement['announcement_title'],
					'create_dateline'=>$oAnnouncement['create_dateline'],
					'announcement_url'=>$oAnnouncement['announcement_type']==1?$oAnnouncement['announcement_message']:Dyhb::U('home://msg@?id='.$oAnnouncement['announcement_id']),
				);
			}
		}

		Core_Extend::saveSyscache('announcement',$arrData);
	}

}
