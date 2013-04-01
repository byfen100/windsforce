<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子分类设置对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CategorytopicdialogController extends Controller{

	public function index(){
		$nGroupid=intval(G::getGpc('groupid','G'));
		$arrGrouptopics=G::getGpc('dataids','G');
		$nCategoryid=intval(G::getGpc('category_id','G'));
		

		if(empty($nGroupid)){
			$this->E('没有待操作的小组');
		}

		$oGroup=GroupModel::F('group_id=?',$nGroupid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E('没有找到指定的小组');
		}
		
		if(empty($arrGrouptopics)){
			exit('没有待操作的帖子');
		}
		
		$sGrouptopics=implode(',',$arrGrouptopics);
		$this->assign('sGrouptopics',$sGrouptopics);

		$this->assign('nGroupid',$nGroupid);
		
		// 取得小组的分类
		$arrGrouptopiccategorys=GrouptopiccategoryModel::F('group_id=?',$nGroupid)->order('grouptopiccategory_sort ASC,create_dateline DESC')->getAll();

		$this->assign('arrGrouptopiccategorys',$arrGrouptopiccategorys);

		$this->assign('nCategoryid',$nCategoryid);

		$this->display('grouptopicadmin+categorytopicdialog');
	}

}
