<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组分类删除设置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CategorydeleteController extends Controller{

	public function index(){
		// 获取参数
		$sId=trim(G::getGpc('gid','G'));
		$nCategoryId=intval(G::getGpc('cid','G'));

		if(Core_Extend::isPostInt($sId)){
			$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}else{
			$oGroup=GroupModel::F('group_name=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}

		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller'));
		}
		
		$oGroupcategory=GroupcategoryModel::F('groupcategory_id=?',$nCategoryId)->query();
		if(!empty($oGroupcategory['groupcategory_id']) || $nCategoryId){
			$oGroupModel=Dyhb::instance('GroupModel');
			$oGroupModel->afterDelete($oGroup['group_id'],$nCategoryId);
			
			$this->S(Dyhb::L('删除群组分类成功','Controller'));
		}else{
			$this->E(Dyhb::L('你要删除的分类不存在','Controller'));
		}
	}

}