<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组入口控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入群组模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/group/App/Class/Model');

class GroupmainController extends InitController{

	public function index($sModel=null,$bDisplay=true){
		$sType=trim(G::getGpc('type','G'));
		
		$arrOptionData=$GLOBALS['_cache_']['group_option'];

		$this->assign('nUploadMaxFilesize',ini_get('upload_max_filesize'));
		$this->assign('nUploadSize',Core_Extend::getUploadSize($arrOptionData['group_icon_uploadfile_maxsize']));
		$this->assign('nUploadSizeTwo',Core_Extend::getUploadSize($arrOptionData['group_headbg_uploadfile_maxsize']));
		$this->assign('nId',intval(G::getGpc('id','G')));
		$this->assign('arrOptions',$arrOptionData);

		$this->display(Admin_Extend::template('group','groupoption/'.($sType?$sType:'index')));
	}

	public function update_option(){
		$arrOptions=G::getGpc('options','P');

		foreach($arrOptions as $sKey=>$val){
			$val=trim($val);
			$oOptionModel=GroupoptionModel::F('groupoption_name=?',$sKey)->getOne();
			$oOptionModel->groupoption_value=G::html($val);
			$oOptionModel->save(0,'update');

			if($oOptionModel->isError()){
				$this->E($oOptionModel->getErrorMessage());
			}
		}

		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('group_option');

		$this->S(Dyhb::L('配置更新成功','__APP_ADMIN_LANG__@Controller/Groupoption'));
	}

}
