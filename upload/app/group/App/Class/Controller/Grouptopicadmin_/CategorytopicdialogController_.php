<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子分类设置对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CategorytopicdialogController extends Controller{

	public function index(){
		$nGroupid=intval(G::getGpc('groupid','G'));
		$sGrouptopicid=trim(G::getGpc('grouptopicid','G'));
		$nCategoryid=intval(G::getGpc('category_id','G'));
		
		if(empty($nGroupid)){
			$this->E(Dyhb::L('没有待操作的小组','Controller/Grouptopicadmin'));
		}

		$oGroup=GroupModel::F('group_id=?',$nGroupid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('没有找到指定的小组','Controller/Grouptopicadmin'));
		}
		
		$arrGrouptopicid=Dyhb::normalize($sGrouptopicid);
		$sGrouptopics=implode(',',$arrGrouptopicid);
		
		if(empty($sGrouptopicid)){
			$this->E(Dyhb::L('没有待操作的帖子','Controller/Grouptopicadmin'));
		}
		
		// 取得小组的分类
		$arrGrouptopiccategorys=GrouptopiccategoryModel::F('group_id=?',$nGroupid)->order('grouptopiccategory_sort ASC,create_dateline DESC')->getAll();
		
		if(isset($_GET['category_id'])){
			$this->assign('nCategoryid',$nCategoryid);
		}

		$this->assign('sGrouptopics',$sGrouptopics);
		$this->assign('nGroupid',$nGroupid);
		$this->assign('arrGrouptopiccategorys',$arrGrouptopiccategorys);
		$this->assign('sTitle',Dyhb::L('你选择了 %d 篇帖子','Controller/Grouptopicadmin',null,count($arrGrouptopicid)));

		$this->display('grouptopicadmin+categorytopicdialog');
	}

}
