<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动相关($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class EventController extends InitController{

	public function add(){
		Core_Extend::doControllerAction('Event@Add','index');
	}
	
	public function add_in(){
		Core_Extend::doControllerAction('Event@Addin','index');
	}

}
