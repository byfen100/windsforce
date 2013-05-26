<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   保存小组设置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SaveController extends Controller{

	public function index(){
		// 获取参数
		$nId=intval(G::getGpc('gid'));
		$sGroupname=trim(G::getGpc('group_name'));

		$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$nId)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller/Group'));
		}

		if($oGroup['group_name'] && $sGroupname){
			$this->E(Dyhb::L('小组已经设置过了，你无法修改','Controller/Groupadmin'));
		}

		$oGroup->save(0,'update');

		if($oGroup->isError()){
			$this->E($oGroup->getErrorMessage());
		}

		$this->S(Dyhb::L('小组设置已经更新','Controller/Groupadmin'));
	}

}