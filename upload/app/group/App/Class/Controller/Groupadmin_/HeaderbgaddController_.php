<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   保存小组头部背景设置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HeaderbgaddController extends Controller{

	public function index(){
		// 获取参数
		$nId=intval(G::getGpc('gid'));
		$sGroupname=trim(G::getGpc('group_name'));

		$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$nId)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller/Group'));
		}

		if($_FILES['headerbg']['error'][0]=='4'){
			if(isset($_POST['group_headerbg'])){
				$oGroup->group_headerbg=intval($_POST['group_headerbg']);
				$oGroup->save(0,'update');

				if($oGroup->isError()){
					$this->E($oGroup->getErrorMessage());
				}
			}
		}else{
			require_once(Core_Extend::includeFile('function/Upload_Extend'));
			try{
				// 上传前删除早前的icon
				if(!empty($oGroup['group_headerbg']) && !Core_Extend::isPostInt($oGroup['group_headerbg'])){
					require_once(Core_Extend::includeFile('function/Upload_Extend'));
					Upload_Extend::deleteIcon('group',$oGroup['group_headerbg']);
			
					$oGroup->group_headerbg='';
					$oGroup->save(0,'update');
					if($oGroup->isError()){
						$this->E($oGroup->getErrorMessage());
					}
				}

				// 执行上传
				$sPhotoDir=Upload_Extend::uploadIcon('group',array('width'=>1170,'height'=>150,'uploadfile_maxsize'=>$GLOBALS['_cache_']['group_option']['group_headbg_uploadfile_maxsize']));
			
				$oGroup->group_headerbg=$sPhotoDir;
				$oGroup->save(0,'update');
				if($oGroup->isError()){
					$this->E($oGroup->getErrorMessage());
				}
			}catch(Exception $e){
				$this->E($e->getMessage());
			}
		}

		$this->S(Dyhb::L('群组背景设置成功','Controller/Groupadmin'));
	}

}