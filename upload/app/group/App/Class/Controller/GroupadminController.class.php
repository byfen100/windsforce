<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组管理控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GroupadminController extends InitController{

	public function init__(){
		parent::init__();

		$this->is_login();

		$nGroupid=intval(G::getGpc('gid'));

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($nGroupid);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		if(!Groupadmin_Extend::checkAdminGroupRbac($nGroupid)){
			$this->E(Dyhb::L('你没有管理小组的权限','Controller/Groupadmin'));
		}
	}
	
	public function index(){
		Core_Extend::doControllerAction('Groupadmin@Index','index');
	}

	public function save(){
		Core_Extend::doControllerAction('Groupadmin@Save','index');
	}

	public function headerbg(){
		Core_Extend::doControllerAction('Groupadmin@Headerbg','index');
	}

	public function headerbg_add(){
		Core_Extend::doControllerAction('Groupadmin@Headerbgadd','index');
	}
	
	public function headerbg_delete(){
		Core_Extend::doControllerAction('Groupadmin@Headerbgdelete','index');
	}

	public function icon(){
		Core_Extend::doControllerAction('Groupadmin@Icon','index');
	}

	public function icon_add(){
		Core_Extend::doControllerAction('Groupadmin@Iconadd','index');
	}

	public function icon_delete(){
		Core_Extend::doControllerAction('Groupadmin@Icondelete','index');
	}

	public function category(){
		Core_Extend::doControllerAction('Groupadmin@Category','index');
	}

	public function category_add(){
		Core_Extend::doControllerAction('Groupadmin@Categoryadd','index');
	}

	public function category_delete(){
		Core_Extend::doControllerAction('Groupadmin@Categorydelete','index');
	}

	public function topiccategory(){
		Core_Extend::doControllerAction('Groupadmin@Topiccategory','index');
	}

	public function topiccategory_add(){
		Core_Extend::doControllerAction('Groupadmin@Topiccategoryadd','index');
	}

	public function updatetopiccategory(){
		Core_Extend::doControllerAction('Groupadmin@Updatetopiccategory','index');
	}

	public function topiccategory_update(){
		Core_Extend::doControllerAction('Groupadmin@Topiccategoryupdate','index');
	}

	public function topiccategory_delete(){
		Core_Extend::doControllerAction('Groupadmin@Topiccategorydelete','index');
	}

	public function user_delete(){
		Core_Extend::doControllerAction('Groupadmin@Userdelete','index');
	}

	public function admins_update(){
		Core_Extend::doControllerAction('Groupadmin@Adminsupdate','index');
	}

}
