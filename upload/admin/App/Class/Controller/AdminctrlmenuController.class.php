<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   后台快捷访问控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AdminctrlmenuController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['adminctrlmenu_title']=array('like',"%".G::getGpc('adminctrlmenu_title')."%");
	}

	protected function aInsert($nId=null){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('adminctrlmenu');
	}

	public function afterInputChangeAjax($sName=null){
		$this->aInsert();
	}

	protected function aUpdate($nId=null){
		$this->aInsert();
	}

	public function aForeverdelete($sId){
		$this->aInsert();
	}

	protected function aForbid(){
		$this->aInsert();
	}
	
	protected function aResume(){
		$this->aInsert();
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function clicknum(){
		$nId=intval(G::getGpc('id','G'));

		if(empty($nId)){
			return;
		}

		$oAdminctrlmenu=AdminctrlmenuModel::F('adminctrlmenu_id=?',$nId)->getOne();
		if(empty($oAdminctrlmenu['adminctrlmenu_id'])){
			return;
		}

		$oAdminctrlmenu->adminctrlmenu_clicknum=$oAdminctrlmenu->adminctrlmenu_clicknum+1;
		$oAdminctrlmenu->save(0,'update');

		if($oAdminctrlmenu->isError()){
			return;
		}

		echo $oAdminctrlmenu->adminctrlmenu_clicknum;
	}

	public function add_url(){
		$sUrl=trim(G::getGpc('url','G'));
		$sTitle=trim(G::getGpc('title','G'));

		$sUrl=parse_url($sUrl,PHP_URL_QUERY);

		$oAdminctrlmenu=AdminctrlmenuModel::F('adminctrlmenu_url=? AND adminctrlmenu_internal=1',$sUrl)->getOne();
		if(!empty($oAdminctrlmenu['adminctrlmenu_id'])){
			if($oAdminctrlmenu->adminctrlmenu_status==1){
				$this->E(Dyhb::L('快捷访问导航已经被添加','Controller'));
			}else{
				$oAdminctrlmenu->adminctrlmenu_status=1;
				$oAdminctrlmenu->save(0,'update');

				if($oAdminctrlmenu->isError()){
					$oAdminctrlmenu->E($oAdminctrlmenu->getErrorMessage());
				}else{
					$this->aInsert();

					$this->S(Dyhb::L('添加快捷访问导航成功','Controller'));
				}
			}
		}else{
			$oAdminctrlmenu=new AdminctrlmenuModel();
			$oAdminctrlmenu->adminctrlmenu_title=$sTitle;
			$oAdminctrlmenu->adminctrlmenu_url=$sUrl;
			$oAdminctrlmenu->adminctrlmenu_internal=1;
			$oAdminctrlmenu->adminctrlmenu_status=1;
			$oAdminctrlmenu->save(0);

			if($oAdminctrlmenu->isError()){
				$oAdminctrlmenu->E($oAdminctrlmenu->getErrorMessage());
			}else{
				$this->aInsert();

				$this->S(Dyhb::L('添加快捷访问导航成功','Controller'));
			}
		}
	}

}
