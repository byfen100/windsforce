<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   站点统计缓存($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AppUpdateCacheSite{

	public static function cache(){
		$arrData=array();

		$arrData['grouptopic']=GrouptopicModel::F()->all()->getCounts();
		$arrData['grouptopiccomment']=GrouptopiccommentModel::F()->all()->getCounts();

		Core_Extend::saveSyscache('group_site',$arrData);
	}

}
