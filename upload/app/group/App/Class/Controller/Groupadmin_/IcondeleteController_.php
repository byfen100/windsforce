<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   删除小组图标设置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IcondeleteController extends Controller{

	public function index(){
		// 获取参数
		$nId=intval(G::getGpc('gid'));
		$sGroupname=trim(G::getGpc('group_name'));

		$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$nId)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller/Group'));
		}

		if(!empty($oGroup['group_icon'])){
			require_once(Core_Extend::includeFile('function/Upload_Extend'));
			Upload_Extend::deleteIcon('group',$oGroup['group_icon']);
		
			$oGroup->group_icon='';
			$oGroup->save(0,'update');
			if($oGroup->isError()){
				$this->E($oGroup->getErrorMessage());
			}
			
			$this->S(Dyhb::L('图标删除成功','Controller/Groupadmin'));
		}else{
			$this->E(Dyhb::L('图标不存在','Controller/Groupadmin'));
		}
	}

}