<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   用户申诉首页($)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		if(UserModel::M()->isLogin()){
			$this->U('home://ucenter/index');
		}

		$this->display('userappeal+index');
	}

	public function index_title_(){
		return Dyhb::L('用户申诉','Controller/Userappeal');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
