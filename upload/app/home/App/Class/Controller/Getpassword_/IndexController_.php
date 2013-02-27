<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   找回密码首页($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		if(UserModel::M()->isLogin()){
			$this->U('home://spaceadmin/password');
		}
	
		$this->display('getpassword+index');
	}

	public function index_title_(){
		return Dyhb::L('找回密码','Controller/Getpassword');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
