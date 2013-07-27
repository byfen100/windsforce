<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加小组帖子分类设置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TopiccategoryaddController extends Controller{

	public function index(){
		// 获取参数
		$sId=trim(G::getGpc('gid'));

		if(Core_Extend::isPostInt($sId)){
			$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}else{
			$oGroup=GroupModel::F('group_name=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}

		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller'));
		}

		// 保存分类
		$oGrouptopiccategory=Dyhb::instance('GrouptopiccategoryModel');
		$oGrouptopiccategory->insertGroupcategory($oGroup['group_id']);

		if($oGrouptopiccategory->isError()){
			$this->E($oGrouptopiccategory->getErrorMessage());
		}else{
			$this->S(Dyhb::L('添加帖子分类成功','Controller'));
		}
	}

}