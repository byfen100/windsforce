<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组操作控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GrouptopicadminController extends InitController{

	public function deletetopic_dialog(){
		Core_Extend::doControllerAction('Grouptopicadmin@Deletetopicdialog','index');
	}

	public function deletetopic(){
		Core_Extend::doControllerAction('Grouptopicadmin@Deletetopic','index');
	}

	public function closetopic_dialog(){
		Core_Extend::doControllerAction('Grouptopicadmin@Closetopicdialog','index');
	}

	public function closetopic(){
		Core_Extend::doControllerAction('Grouptopicadmin@Closetopic','index');
	}

	public function sticktopic_dialog(){
		Core_Extend::doControllerAction('Grouptopicadmin@Sticktopicdialog','index');
	}

	public function sticktopic(){
		Core_Extend::doControllerAction('Grouptopicadmin@Sticktopic','index');
	}

	public function digesttopic_dialog(){
		Core_Extend::doControllerAction('Grouptopicadmin@Digesttopicdialog','index');
	}

	public function digesttopic(){
		Core_Extend::doControllerAction('Grouptopicadmin@Digesttopic','index');
	}

	public function recommendtopic_dialog(){
		Core_Extend::doControllerAction('Grouptopicadmin@Recommendtopicdialog','index');
	}

	public function recommendtopic(){
		Core_Extend::doControllerAction('Grouptopicadmin@Recommendtopic','index');
	}

	public function statustopic_dialog(){
		Core_Extend::doControllerAction('Grouptopicadmin@Statustopicdialog','index');
	}

	public function statustopic(){
		Core_Extend::doControllerAction('Grouptopicadmin@Statustopic','index');
	}

	public function categorytopic_dialog(){
		Core_Extend::doControllerAction('Grouptopicadmin@Categorytopicdialog','index');
	}

	public function categorytopic(){
		Core_Extend::doControllerAction('Grouptopicadmin@Categorytopic','index');
	}

	public function tagtopic_dialog(){
		Core_Extend::doControllerAction('Grouptopicadmin@Tagtopicdialog','index');
	}

	public function tagtopic(){
		Core_Extend::doControllerAction('Grouptopicadmin@Tagtopic','index');
	}

	public function colortopic_dialog(){
		Core_Extend::doControllerAction('Grouptopicadmin@Colortopicdialog','index');
	}

	public function colortopic(){
		Core_Extend::doControllerAction('Grouptopicadmin@Colortopic','index');
	}

}
