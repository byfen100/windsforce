<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   群组操作控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GrouptopicadminController extends InitController{

	public function deletetopic_dialog(){
		Core_Extend::doControllerAction('Grouptopicadmin@Deletetopicdialog','index');
	}

	public function deletetopic(){
		Core_Extend::doControllerAction('Grouptopicadmin@Deletetopic','index');
	}

}
