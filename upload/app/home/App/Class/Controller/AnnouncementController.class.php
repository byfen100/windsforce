<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   公告控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AnnouncementController extends InitController{

	public function index(){
		Core_Extend::doControllerAction('Announcement@Index','index',$this);
	}

	public function show(){
		Core_Extend::doControllerAction('Announcement@Show','index',$this);
	}

}
