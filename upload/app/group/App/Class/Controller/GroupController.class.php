<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   群组控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GroupController extends InitController{

	public function show(){
		Core_Extend::doControllerAction('Group@Show','index');
	}

	public function joingroup(){
		Core_Extend::doControllerAction('Group@Joingroup','index');
	}

	public function leavegroup(){
		Core_Extend::doControllerAction('Group@Leavegroup','index');
	}

	public function getcategory(){
		Core_Extend::doControllerAction('Group@Getcategory','index');
	}

}
