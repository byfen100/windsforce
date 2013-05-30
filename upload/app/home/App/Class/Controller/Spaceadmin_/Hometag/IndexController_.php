<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   标签首页($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		$oUser=UserModel::F()->getByuser_id($GLOBALS['___login___']['user_id']);

		$arrHometags=array();
		$oTag=Dyhb::instance('HometagModel');
		$arrHometags=$oTag->getTagsByUserid($oUser['user_id']);

		$this->assign('oUser',$oUser);
		$this->assign('arrHometags',$arrHometags);

		$this->display('spaceadmin+tag');
	}

	public function tag_title_(){
		return Dyhb::L('用户标签','Controller');
	}

	public function tag_keywords_(){
		return $this->tag_title_();
	}

}
