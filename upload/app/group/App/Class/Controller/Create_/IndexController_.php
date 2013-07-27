<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   创建小组界面控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		// 小组分类
		$oGroupcategory=Dyhb::instance('GroupcategoryModel');
		$oGroupcategoryTree=$oGroupcategory->getGroupcategoryTree();

		$this->assign('oGroupcategoryTree',$oGroupcategoryTree);
		
		$this->display('create+index');
	}
	
	public function index_title_(){
		return Dyhb::L('申请创建小组','Controller');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}