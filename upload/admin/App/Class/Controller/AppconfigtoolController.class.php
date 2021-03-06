<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   应用配置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AppconfigtoolController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller'));
		}
	}
	
	public function index($sName=null,$bDisplay=true){
		$sAppGlobalconfigFile=WINDSFORCE_PATH.'/config/Config.inc.php';
		if(!is_file($sAppGlobalconfigFile)){
			$this->E(Dyhb::L('框架全局配置文件 %s 不存在','Controller',null,$sAppGlobalconfigFile));
		}

		$sAppGlobalconfig=nl2br(htmlspecialchars(file_get_contents($sAppGlobalconfigFile)));
		$arrAppGlobalconfigs=(array)(include $sAppGlobalconfigFile);

		$this->assign('sAppGlobalconfig',$sAppGlobalconfig);
		$this->assign('sAppGlobalconfigFile',str_replace(G::tidyPath(WINDSFORCE_PATH),'{WINDSFORCE_PATH}',G::tidyPath($sAppGlobalconfigFile)));
		$this->assign('arrAppGlobalconfigs',$arrAppGlobalconfigs);
		$this->assign('sAppGlobaldefaultconfigFile','{WINDSFORCE_PATH}/config/ConfigDefault.inc.php');

		$arrWhere=array();
		$arrWhere['app_status']=1;

		$nTotalRecord=AppModel::F()->where($arrWhere)->all()->getCounts();

		$nEverynum=$GLOBALS['_option_']['admin_list_num'];
		$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));

		$arrSaveLists=array();
		
		$sConfigfile='admin/App/Config/Config.php';
		$sConfigcachefile='data/~runtime/app/admin/Config.php';

		$arrSaveLists[0]=array(
			'app_id'=>0,
			'app_identifier'=>'admin',
			'app_name'=>Dyhb::L('全局后台','Controller'),
			'logo'=>__ROOT__.'/admin/logo.png',
			'config_file'=>'{WINDSFORCE_PATH}/'.$sConfigfile,
			'config_file_exist'=>is_file(WINDSFORCE_PATH.'/'.$sConfigfile)?true:false,
			'config_cache_file'=>'{WINDSFORCE_PATH}/'.$sConfigcachefile,
			'config_cache_file_exist'=>is_file(WINDSFORCE_PATH.'/'.$sConfigcachefile)?true:false,
		);

		$arrLists=AppModel::F()->where($arrWhere)->all()->order('app_id DESC')->limit($oPage->returnPageStart(),$nEverynum)->query();
		if(is_array($arrLists)){
			foreach($arrLists as $oList){
				$sConfigfile='app/'.$oList['app_identifier'].'/App/Config/Config.php';
				$sConfigcachefile='data/~runtime/app/'.$oList['app_identifier'].'/Config.php';

				$arrSaveLists[$oList['app_id']]=array(
					'app_id'=>$oList['app_id'],
					'app_identifier'=>$oList['app_identifier'],
					'app_name'=>$oList['app_name'],
					'logo'=>Core_Extend::appLogo($oList['app_identifier']),
					'config_file'=>'{WINDSFORCE_PATH}/'.$sConfigfile,
					'config_file_exist'=>is_file(WINDSFORCE_PATH.'/'.$sConfigfile)?true:false,
					'config_cache_file'=>'{WINDSFORCE_PATH}/'.$sConfigcachefile,
					'config_cache_file_exist'=>is_file(WINDSFORCE_PATH.'/'.$sConfigcachefile)?true:false,
				);
			}
		}

		$this->assign('sPageNavbar',$oPage->P());
		$this->assign('arrLists',$arrSaveLists);

		$this->display();
	}

	public function default_config(){
		$sAppGlobaldefaultconfigFile=WINDSFORCE_PATH.'/config/ConfigDefault.inc.php';
		if(!is_file($sAppGlobaldefaultconfigFile)){
			$this->error_message(Dyhb::L('框架全局惯性配置文件 %s 不存在','Controller',null,$sAppGlobaldefaultconfigFile));
		}

		$sAppGlobaldefaultconfig=nl2br(htmlspecialchars(file_get_contents($sAppGlobaldefaultconfigFile)));
		$arrAppGlobaldefaultconfigs=(array)(include $sAppGlobaldefaultconfigFile);

		$this->assign('sAppGlobaldefaultconfig',$sAppGlobaldefaultconfig);
		$this->assign('arrAppGlobaldefaultconfigs',$arrAppGlobaldefaultconfigs);

		$this->display();
	}

	public function app_config(){
		$sApp=trim(strtolower(G::getGpc('app')));
		$sType=trim(G::getGpc('type'));

		$sAppConfigfile=$this->get_configfile($sApp);

		if($sType=='file'){
			if(!is_file($sAppConfigfile)){
				$this->error_message(Dyhb::L('应用配置文件 %s 不存在','Controller',null,$sAppConfigfile));
			}

			$sAppconfig=nl2br(htmlspecialchars(file_get_contents($sAppConfigfile)));
			$this->assign('sAppconfig',$sAppconfig);

			$this->display('appconfigtool+config_file');
		}else{
			$sAppConfigcachefile=$this->get_configcachefile($sApp);
			if(!is_file($sAppConfigcachefile)){
				$this->error_message(Dyhb::L('应用配置缓存文件 %s 不存在','Controller',null,$sAppConfigcachefile));
			}

			$arrAppFrameworkdefaultconfigs=(array)(include DYHB_PATH.'/Config_/DefaultConfig.inc.php');
			$arrAppconfigs=(array)(include $sAppConfigcachefile);
			
			$this->assign('arrAppconfigs',$arrAppconfigs);
			$this->assign('arrAppFrameworkdefaultconfigs',$arrAppFrameworkdefaultconfigs);

			$this->display();
		}
	}

	public function delete_appconfigs(){
		$arrKeys=G::getGpc('key','P');
		
		$sLastApp='';
		if(!empty($arrKeys)){
			foreach($arrKeys as $sApp){
				$this->delete_appconfig($sApp,false);
				$sLastApp=$sApp;
			}
		}

		$this->assign('__JumpUrl__',Dyhb::U('appconfigtool/index?extra='.$sLastApp).'#apps');
		$this->S(Dyhb::L('批量清除应用的缓存配置成功','Controller'));
	}
	
	public function delete_appconfig($sApp=null,$bMessage=true){
		if(is_null($sApp)){
			$sApp=trim(strtolower(G::getGpc('app')));
		}

		$sAppConfigcachefile=$this->get_configcachefile($sApp);
		@unlink($sAppConfigcachefile);
	
		if($bMessage===true){
			$this->assign('__JumpUrl__',Dyhb::U('appconfigtool/index?extra='.$sApp).'#apps');
			$this->S(Dyhb::L('清除应用 %s 的缓存配置成功','Controller',null,$sApp));
		}
	}

	public function edit_appconfig(){
		$sApp=trim(G::getGpc('app','G'));
		
		if($sApp!='admin'){
			$oApp=AppModel::F('app_identifier=? AND app_status=1',$sApp)->getOne();
			if(empty($oApp['app_id'])){
				$this->error_message(Dyhb::L('应用 %s 不存在或者尚未开启','Controller',null,$sApp));
			}
		}

		if($sApp=='admin'){
			$sAppConfigPath=WINDSFORCE_PATH.'/admin/App/Config';
		}else{
			$sAppConfigPath=WINDSFORCE_PATH.'/app/'.$sApp.'/App/Config';
		}

		if(!is_dir($sAppConfigPath)){
			$this->error_message(Dyhb::L('应用 %s 配置目录不存在','Controller',null,$sApp));
		}

		$arrConfigfiles=array();
		$arrConfigfiles=G::listDir($sAppConfigPath,true,true);
		if(is_dir($sAppConfigPath.'/ExtendConfig')){
			$arrExtendconfigfiles=G::listDir($sAppConfigPath.'/ExtendConfig',true,true);
			foreach($arrExtendconfigfiles as $sExtendconfigfile){
				$arrConfigfiles[]=$sExtendconfigfile;
			}
		}

		$arrSaveDatas=array();
		foreach($arrConfigfiles as $nKey=>$sFile){
			$arrSaveDatas[$nKey]=array(
				'really_file'=>$sFile,
				'file'=>str_replace(G::tidyPath(WINDSFORCE_PATH),'{WINDSFORCE_PATH}',G::tidyPath($sFile)),
				'content'=>file_get_contents($sFile),
			);
		}
		
		$this->assign('arrConfigfiles',$arrSaveDatas);
		$this->assign('sApp',$sApp);
		
		$this->display();
	}

	public function save_appconfig(){
		$sApp=G::getGpc('app','P');
		$arrDatas=G::getGpc('data','P');
		
		foreach($arrDatas as $sKey=>$sData){
			$sReallyconfigfile=str_replace('{WINDSFORCE_PATH}',G::tidyPath(WINDSFORCE_PATH),G::tidyPath($sKey));
			if(!@file_put_contents($sReallyconfigfile,$sData)){
				$this->E(Dyhb::L('应用配置文件 %s 不可写','Controller',null,$sReallyconfigfile));
			}
		}

		$this->delete_appconfig($sApp,false);

		$this->S(Dyhb::L('应用 %s 配置文件修改成功','Controller',null,$sApp));
	}

	public function edit_globalconfig(){
		$sAppGlobalconfigFile=WINDSFORCE_PATH.'/config/Config.inc.php';
		if(!is_file($sAppGlobalconfigFile)){
			$this->error_message(Dyhb::L('框架全局配置文件 %s 不存在','Controller',null,$sAppGlobalconfigFile));
		}

		$sAppGlobalconfig=file_get_contents($sAppGlobalconfigFile);

		$this->assign('sAppGlobalconfig',$sAppGlobalconfig);
		$this->assign('sAppGlobalconfigFile',str_replace(G::tidyPath(WINDSFORCE_PATH),'{WINDSFORCE_PATH}',G::tidyPath($sAppGlobalconfigFile)));

		$this->display();
	}

	public function save_globalconfig(){
		$sData=G::getGpc('data','P');

		$sAppGlobalconfigFile=WINDSFORCE_PATH.'/config/Config.inc.php';
		if(!@file_put_contents($sAppGlobalconfigFile,$sData)){
			$this->E(Dyhb::L('全局配置文件 %s 不可写','Controller',null,$sAppGlobalconfigFile));
		}

		$arrSaveDatas=array();

		$arrWhere=array();
		$arrWhere['app_status']=1;
		$arrApps=AppModel::F()->where($arrWhere)->all()->query();
		if(is_array($arrApps)){
			foreach($arrApps as $oApp){
				$arrSaveDatas[]=$oApp['app_identifier'];
			}
		}
		$arrSaveDatas[]='admin';

		foreach($arrSaveDatas as $sApp){
			$this->delete_appconfig($sApp,false);
		}

		$this->S(Dyhb::L('全局配置文件 %s 修改成功','Controller',null,$sAppGlobalconfigFile));
	}

	public function get_configfile($sApp){
		if($sApp!='admin'){
			$oApp=AppModel::F('app_identifier=? AND app_status=1',$sApp)->getOne();
			if(empty($oApp['app_id'])){
				$this->error_message(Dyhb::L('应用 %s 不存在或者尚未开启','Controller',null,$sApp));
			}
		}

		if($sApp=='admin'){
			$sAppConfigfile=WINDSFORCE_PATH.'/admin/App/Config/Config.php';
		}else{
			$sAppConfigfile=WINDSFORCE_PATH.'/app/'.$sApp.'/App/Config/Config.php';
		}

		return $sAppConfigfile;
	}

	public function get_configcachefile($sApp){
		return WINDSFORCE_PATH.'/data/~runtime/app/'.$sApp.'/Config.php';
	}

	public function error_message($sMessage){
		$this->assign('sMessage',$sMessage);

		$this->display('appconfigtool+message');
		exit();
	}

	public function filter_value($Value){
		if(is_array($Value)){
			return G::dump($Value,false);
		}
		
		if($Value===false){
			return 'FALSE';
		}

		if($Value===true){
			return 'TRUE';
		}

		if($Value===null){
			return 'NULL';
		}

		if($Value==''){
			return "''";
		}

		return $Value;
	}

}
