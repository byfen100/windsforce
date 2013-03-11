<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   安装应用控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入Home模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/home/App/Class/Model');

/** 定义Home的语言包 */
define('__APP_ADMIN_LANG__',WINDSFORCE_PATH.'/app/home/App/Lang/Admin');

class InstallappController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller/Common'));
		}
	}
	
	public function index($sModel=null,$bDisplay=true){
		$arrInstalledApps=array();
		$arrInstalleds=AppModel::F()->getAll();
		if(is_array($arrInstalleds)){
			foreach($arrInstalleds as $oInstalled){
				$arrInstalledApps[]=$oInstalled['app_identifier'];
			}
		}

		$arrAppLists=G::ListDir(WINDSFORCE_PATH.'/app');

		$arrAppInfos=array();
		$arrApps=array();
		foreach($arrAppLists as $key=>$sAppList){
			if($sAppList!='.svn' && !in_array($sAppList,$arrInstalledApps) && is_file(WINDSFORCE_PATH.'/app/'.$sAppList.'/app.xml')){
				$arrAppInfos[$key]['name']=$sAppList;
				
				$arrAppData=Xml::xmlUnserialize(file_get_contents(WINDSFORCE_PATH.'/app/'.$sAppList.'/app.xml'));
				if($arrAppData===null){
					$this->E(Dyhb::L('应用%s的配置文件app.xml无法读取','Controller/App',null,$sAppList));
				}

				$arrAppData=$arrAppData['root']['data'];
				$arrAppData['logo']=Core_Extend::appLogo($sAppList);

				$arrAppInfos[$key]['app']=$arrAppData;
			}
		}

		foreach($arrAppInfos as $arrAppInfo){
			$arrApps[]=$arrAppInfo;
		}

		$this->assign('arrApps',$arrApps);
		$this->display();
	}

	public function import_app(){
		$sName=G::getGpc('name','G');
		
		if(is_file(WINDSFORCE_PATH.'/app/'.$sName.'/app.xml')){
			$sImportTxt=file_get_contents(WINDSFORCE_PATH.'/app/'.$sName.'/app.xml');
			$arrAppData=Xml::xmlUnserialize(trim($sImportTxt));
			$arrAppData=$arrAppData['root']['data'];
			
			if(!$this->is_app_key($arrAppData['identifier'])) {
				$this->E(Dyhb::L('应用唯一标识符存在非法字符','Controller/App'));
			}
			
			$oTryApp=AppModel::F('app_identifier=?',$arrAppData['identifier'])->getOne();
			if(!empty($oTryApp['app_id'])) {
				$this->E(Dyhb::L('导入的应用 %s 已经存在','Controller/App',null,$oTryApp['app_identifier']));
			}
			
			$this->app_database($arrAppData);
			
			if(!empty($sName) && $arrAppData['isinstall'] && is_file(WINDSFORCE_PATH.'/app/'.$sName.'/install.php')){
				$this->U('installapp/import_install&name='.$sName);
			}

			$this->cache_site_();
			$this->S(Dyhb::L('应用 %s 安装成功','Controller/App',null,$sName));
		}else{
			$this->E(Dyhb::L('你准备安装的应用不存在','Controller/App'));
		}
	}
	
	public function app_database($arrAppData){
		if(!$arrAppData || !$arrAppData['identifier']){
			return false;
		}
		
		$oTryApp=AppModel::F('app_identifier=?',$arrAppData['identifier'])->getOne();
		if(!empty($oTryApp['app_id'])){
			return false;
		}
		
		$arrData=array();
		foreach($arrAppData as $sKey=>$sVal){
			if($sKey=='status'){
				$sVal=0;
			}
			$arrData['app_'.$sKey]=$sVal;
		}
		
		$oApp=new AppModel($arrData);
		$oApp->save(0);
		if($oApp->isError()){
			$this->E($oApp->getErrorMessage());
		}
		
		return true;
	}

	public function import_install(){
		$sName=trim(G::getGpc('name','G'));

		if(!empty($sName)){
			$this->import_install_or_uninstall('install',$sName);
		}else{
			$this->E(Dyhb::L('你准备安装的应用不存在','Controller/App'));
		}
	}

	public function uninstall_app(){
		$sName=trim(G::getGpc('name','G'));

		if(in_array($sName,array('home','wap','group'))){
			$this->E(Dyhb::L('系统应用无法卸载','Controller/App'));
		}

		$oApp=AppModel::F('app_identifier=?',$sName)->getOne();
		if(!empty($oApp['app_id'])){
			$oApp->destroy();
			
			if(!empty($oApp['app_identifier']) && $oApp['app_isuninstall'] && is_file(WINDSFORCE_PATH.'/app/'.$oApp['app_identifier'].'/uninstall.php')){
				$this->U('installapp/import_uninstall&name='.$oApp['app_identifier']);
			}

			$this->cache_site_();
			
			$this->S(Dyhb::L('应用 %s 卸载成功','Controller/App',null,$oApp['app_identifier']));
		}else{
			$this->E(Dyhb::L('你准备卸载的应用不存在','Controller/App'));
		}
	}

	public function import_uninstall(){
		$sName=trim(G::getGpc('name','G'));

		if(!empty($sName)){
			$nConfirm=intval(G::getGpc('confirm','G'));
			
			if(!$nConfirm){
				$this->assign('sName',$sName);
				$this->display();
			}else{
				$this->import_install_or_uninstall('uninstall',$sName);
			}
		}else{
			$this->E(Dyhb::L('你准备卸载的应用不存在','Controller/App'));
		}
	}

	public function import_install_or_uninstall($sOperation,$sName){
		$bFinish=FALSE;

		if(is_file(WINDSFORCE_PATH.'/app/'.$sName.'/app.xml')){
			$arrAppData=Xml::xmlUnserialize(file_get_contents(WINDSFORCE_PATH.'/app/'.$sName.'/app.xml'));
			$arrAppData=$arrAppData['root']['data'];

			if($sOperation=='install'){
				$sFilename='install.php';
			}elseif($sOperation=='uninstall'){
				$sFilename='uninstall.php';
			}

			if(!empty($sFilename) && preg_match('/^[\w\.]+$/',$sFilename)){
				$sFilename=WINDSFORCE_PATH.'/app/'.$sName.'/'.$sFilename;
		
				if(is_file($sFilename)){
					include_once $sFilename;
				}else{
					$bFinish=TRUE;
				}
			}else{
				$bFinish=TRUE;
			}

			if($bFinish){
				$this->cache_site_();

				if($sOperation=='install'){
					$this->assign('__JumpUrl__',Dyhb::U('app/index'));
					$this->S(Dyhb::L('应用 %s 安装成功','Controller/App',null,$sName));
				}

				if($sOperation=='uninstall'){
					$this->assign('__JumpUrl__',Dyhb::U('app/index'));
					$this->S(Dyhb::L('卸载 %s 卸载成功','Controller/App',null,$sName));
				}
			}
		}else{
			$this->E(Dyhb::L('你准备卸载的应用不存在','Controller/App'));
		}
	}
	
	public function is_app_key($sKey) {
		return preg_match("/^[a-z]+[a-z0-9_]*$/i",$sKey);
	}

	public function runQuery($sSql){
		$nSqlprotected=intval(G::getGpc('sqlprotected','G'));
		
		if($nSqlprotected==1){
			return;
		}
		
		if(empty($sSql)){
			return;
		}

		Admin_Extend::runQuery($sSql);
	}

	protected function cache_site_(){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache("site");
	}

}
