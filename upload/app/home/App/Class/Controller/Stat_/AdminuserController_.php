<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   系统管理员($)*/

!defined('DYHB_PATH') && exit;

class AdminuserController extends Controller{

	public function index(){
		// 取得管理员配置
		$arrAdminuserid=explode(',',$GLOBALS['_commonConfig_']['ADMIN_USERID']);
		if(empty($arrAdminuserid)){
			$arrAdminuserid=array(1);
		}

		// 读取管理员数据
		$arrWhere=array();
		$arrWhere['user_status']=1;
		$arrWhere['user_id']=array('in',$arrAdminuserid);
		$arrUsers=UserModel::F()->where($arrWhere)->order('user_id DESC')->getAll();

		$this->assign('arrUsers',$arrUsers);
		$this->assign('nUsers',count($arrUsers));

		$this->display('stat+adminuser');
	}

	public function adminuser_title_(){
		return Dyhb::L('系统管理员','Controller/Stat');
	}

	public function adminuser_keywords_(){
		return $this->adminuser_title_();
	}

	public function adminuser_description_(){
		return $this->adminuser_title_();
	}
	
}
