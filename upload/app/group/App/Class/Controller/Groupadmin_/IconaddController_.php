<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   保存小组图标设置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IconaddController extends Controller{

	public function index(){
		// 获取参数
		$nId=intval(G::getGpc('gid'));
		$sGroupname=trim(G::getGpc('group_name'));

		$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$nId)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller/Group'));
		}

		require_once(Core_Extend::includeFile('function/Upload_Extend'));
		try{
			// 上传前删除早前的icon
			if(!empty($oGroup['group_icon'])){
				require_once(Core_Extend::includeFile('function/Upload_Extend'));
				Upload_Extend::deleteIcon('group',$oGroup['group_icon']);
		
				$oGroup->group_icon='';
				$oGroup->save(0,'update');
				if($oGroup->isError()){
					$this->E($oGroup->getErrorMessage());
				}
			}

			// 执行上传
			$sPhotoDir=Upload_Extend::uploadIcon('group');
		
			$oGroup->group_icon=$sPhotoDir;
			$oGroup->save(0,'update');
			if($oGroup->isError()){
				$this->E($oGroup->getErrorMessage());
			}
		
			$this->S(Dyhb::L('图标设置成功','Controller/Groupadmin'));
		}catch(Exception $e){
			$this->E($e->getMessage());
		}
	}

}