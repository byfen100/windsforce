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

	public function show(){
		Core_Extend::doControllerAction('Event@Show','index');
	}

	public function join(){
		Core_Extend::doControllerAction('Event@Join','index');
	}

	public function join_in(){
		Core_Extend::doControllerAction('Event@Joinin','index');
	}

	public function attention(){
		Core_Extend::doControllerAction('Event@Attention','index');
	}

}
