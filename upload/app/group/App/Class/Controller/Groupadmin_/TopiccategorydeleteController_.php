<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子分类删除控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TopiccategorydeleteController extends Controller{

	public function index(){
		// 获取参数
		$sId=trim(G::getGpc('gid'));
		$nGrouptopiccategoryid=intval(G::getGpc('cid'));

		if(Core_Extend::isPostInt($sId)){
			$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}else{
			$oGroup=GroupModel::F('group_name=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}

		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller'));
		}
		
		$oGrouptopiccategory=GrouptopiccategoryModel::F('grouptopiccategory_id=? AND group_id=?',$nGrouptopiccategoryid,$oGroup['group_id'])->query();
		if(empty($oGrouptopiccategory['grouptopiccategory_id'])){
			$this->E(Dyhb::L('你删除的帖子分类不存在','Controller'));
		}
		
		// 执行删除
		$oModelMeta=GrouptopiccategoryModel::M();
		$oModelMeta->deleteWhere(array('grouptopiccategory_id'=>$nGrouptopiccategoryid));
			
		if($oModelMeta->isError()){
			$this->E($oModelMeta->getErrorMessage());
		}else{
			// 将分类ID重置为0
			Dyhb::instance('GrouptopicModel')->resetCategory($nGrouptopiccategoryid);
			
			$this->S(Dyhb::L('帖子分类删除成功','Controller'));
		}
	}

}