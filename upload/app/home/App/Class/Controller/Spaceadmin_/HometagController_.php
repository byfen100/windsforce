<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   个人标签管理($)*/

!defined('DYHB_PATH') && exit;

class HometagController extends Controller{

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
		return '用户标签';
	}

	public function tag_keywords_(){
		return $this->tag_title_();
	}

	public function tag_description_(){
		return $this->tag_title_();
	}

	public function add(){
		$nUserId=$GLOBALS['___login___']['user_id'];
		$sHometagName=G::getGpc('hometag_name');

		$oTag=Dyhb::instance('HometagModel');
		$oTag->addTag($nUserId,$sHometagName);

		if($oTag->isError()){
			$this->E($oTag->getErrorMessage());
		}

		$this->S(Dyhb::L('添加用户标签成功','Controller/Spaceadmin'));
	}

	public function delete(){
		$nUserId=$GLOBALS['___login___']['user_id'];
		$nHometagId=intval(G::getGpc('id'));

		$oHometag=HometagindexModel::F('hometag_id=? AND user_id=?',$nHometagId,$nUserId)->getOne();
		if(!empty($oHometag['user_id'])){
			$oHometag->destroy();
		}

		$this->S(Dyhb::L('删除用户标签成功','Controller/Spaceadmin'));
	}

}
