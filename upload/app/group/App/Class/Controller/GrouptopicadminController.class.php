<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组操作控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GrouptopicadminController extends InitController{

	public function init__(){
		parent::init__();

		$this->is_login();

		$nGroupid=intval(G::getGpc('groupid','G'));

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($nGroupid);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}
	}
	
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

	public function hidetopic_dialog(){
		Core_Extend::doControllerAction('Grouptopicadmin@Hidetopicdialog','index');
	}

	public function hidetopic(){
		Core_Extend::doControllerAction('Grouptopicadmin@Hidetopic','index');
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

	public function deletecomment_dialog(){
		Core_Extend::doControllerAction('Grouptopicadmin@Deletecommentdialog','index');
	}

	public function deletecomment(){
		Core_Extend::doControllerAction('Grouptopicadmin@Deletecomment','index');
	}

	public function hidecomment_dialog(){
		Core_Extend::doControllerAction('Grouptopicadmin@Hidecommentdialog','index');
	}

	public function hidecomment(){
		Core_Extend::doControllerAction('Grouptopicadmin@Hidecomment','index');
	}

	public function stickreplycomment_dialog(){
		Core_Extend::doControllerAction('Grouptopicadmin@Stickreplycommentdialog','index');
	}

	public function stickreplycomment(){
		Core_Extend::doControllerAction('Grouptopicadmin@Stickreplycomment','index');
	}

	public function auditcomment_dialog(){
		Core_Extend::doControllerAction('Grouptopicadmin@Auditcommentdialog','index');
	}

	public function auditcomment(){
		Core_Extend::doControllerAction('Grouptopicadmin@Auditcomment','index');
	}

}
