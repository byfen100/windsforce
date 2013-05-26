<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   增加小组分类设置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CategoryaddController extends Controller{

	public function index(){
		// 获取参数
		$sId=trim(G::getGpc('gid'));
		$nCategoryId=intval(G::getGpc('group_categoryid'));

		if(Core_Extend::isPostInt($sId)){
			$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}else{
			$oGroup=GroupModel::F('group_name=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}

		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller/Group'));
		}

		$oGroupcategory=GroupcategoryModel::F('groupcategory_id=?',$nCategoryId)->query();
		if(!empty($oGroupcategory['groupcategory_id']) || $nCategoryId){
			$oExistGroupcategoryindex=GroupcategoryindexModel::F('group_id=? AND groupcategory_id=?',$oGroup['group_id'],$nCategoryId)->query();

			if(!empty($oExistGroupcategoryindex['group_id'])){
				$this->E(Dyhb::L('群组分类已经存在','Controller/Groupadmin'));
			}
			
			$oGroupModel=Dyhb::instance('GroupModel');
			$oGroupModel->afterInsert($oGroup['group_id'],$nCategoryId);
				
			$this->S(Dyhb::L('添加群组分类成功','Controller/Groupadmin'));
		}else{
			$this->E(Dyhb::L('你要添加的分类不存在','Controller/Groupadmin'));
		}
	}

}