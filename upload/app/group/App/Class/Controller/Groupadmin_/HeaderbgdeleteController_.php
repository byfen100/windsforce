<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   删除小组头部背景设置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HeaderbgdeleteController extends Controller{

	public function index(){
		// 获取参数
		$nId=intval(G::getGpc('gid'));
		$sGroupname=trim(G::getGpc('group_name'));

		$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$nId)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller/Group'));
		}

		if(!empty($oGroup['group_headerbg'])){
			if(!Core_Extend::isPostInt($oGroup['group_headerbg'])){
				require_once(Core_Extend::includeFile('function/Upload_Extend'));
				Upload_Extend::deleteIcon('group',$oGroup['group_headerbg']);
			}
		
			$oGroup->group_headerbg='';
			$oGroup->save(0,'update');
			if($oGroup->isError()){
				$this->E($oGroup->getErrorMessage());
			}
			
			$this->S(Dyhb::L('小组背景删除成功','Controller/Groupadmin'));
		}else{
			$this->E(Dyhb::L('小组背景不存在','Controller/Groupadmin'));
		}
	}

}