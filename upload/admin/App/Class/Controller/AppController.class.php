<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   应用管理控制器($)*/

!defined('DYHB_PATH') && exit;

class AppController extends InitController{
	
	public function bIndex_(){
		$arrOptionData=$GLOBALS['_option_'];
		
		// 默认应用
		if(is_file(WINDSFORCE_PATH.'/config/Config.inc.php')){
			$arrConfigs=(array)(include(WINDSFORCE_PATH.'/config/Config.inc.php'));
			$sDefaultAppname=isset($arrConfigs['DEFAULT_APP'])?$arrConfigs['DEFAULT_APP']:'home';
			unset($arrConfigs);
		}else{
			$sDefaultAppname=$arrOptionData['default_app'];
		}

		$this->assign('sDefaultAppname',$sDefaultAppname);
	}

	public function update_option(){Dyhb::L('框架全局惯性配置文件 %s 不存在','Controller/App',null,5);
		if($_POST['options']['default_app']!=$GLOBALS['_option_']['default_app']){
			$sAppGlobaldefaultconfigFile=WINDSFORCE_PATH.'/config/Config.inc.php';
			if(!is_file($sAppGlobaldefaultconfigFile)){
				$this->E(Dyhb::L('框架全局惯性配置文件 %s 不存在','Controller/App',null,$sAppGlobaldefaultconfigFile));
			}

			Core_Extend::changeAppconfig('DEFAULT_APP',$_POST['options']['default_app']);
		}

		$oOptionController=new OptionController();
		$oOptionController->update_option();
	}
	
	public function AInsertObject_($oModel){
		$oModel->filterAppindentifier();

		$oModel->safeInput();
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}
	
	public function config(){
		$nId=intval(G::getGpc('id'));
		
		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定待设置的应用','Controller/App'));
		}else{
			$arrAppModel=AppModel::F('app_id=?',$nId)->getOne();
			if(empty($arrAppModel['app_id'])){
				$this->assign('__JumpUrl__',Dyhb::U('app/index'));
				$this->E(Dyhb::L('你指定待设置的应用不存在','Controller/App'));
			}
			
			if(!$arrAppModel['app_status']){
				$this->assign('__JumpUrl__',Dyhb::U('app/index'));
				$this->E(Dyhb::L('你指定待设置的应用尚未启用','Controller/App'));
			}

			// 定义应用的语言包
			define('__APP_ADMIN_LANG__',WINDSFORCE_PATH.'/app/'.$arrAppModel['app_identifier'].'/App/Lang/Admin');

			// 导入应用扩展函数
			$sExtensionDir=WINDSFORCE_PATH.'/app/'.$arrAppModel['app_identifier'].'/App/Class/Extension';
			if(is_dir($sExtensionDir)){
				Dyhb::import($sExtensionDir);
			}
			
			$this->assign('nId',$nId);
			
			$sController=trim(G::getGpc('controller','G'));
			$sAction=strtolower(trim(G::getGpc('action','G')));

			// 查找模块
			if(empty($sController)){
				$sController=ucfirst($arrAppModel['app_identifier']).'mainController';
				$_GET['controller']=strtolower($arrAppModel['app_identifier']).'main';
			}else{
				$sController=ucfirst($sController).'Controller';
			}
			
			// 查找方法
			if(empty($sAction)){
				$sAction='index';
				$_GET['action']='index';
			}
			
			$sControllerPath=WINDSFORCE_PATH.'/app/'.$arrAppModel['app_identifier'].'/App/Class/Controller/Admin/'.$sController.'_.php';
			if(is_file($sControllerPath)){
				require_once($sControllerPath);
				$oController=null;
				eval('$oController=Dyhb::instance(\''.$sController.'\');');
				
				$callback=array($oController,$sAction);
				if(is_callable($callback)){
					call_user_func($callback);
				}else{
					$this->assign('__JumpUrl__',Dyhb::U('app/index'));
					$this->E(Dyhb::L('后台管理模块 %s 回调不存在','Controller/App',null,G::dump($callback,false)));
				}
				
				exit();
			}else{
				$this->assign('__JumpUrl__',Dyhb::U('app/index'));
				$this->E(Dyhb::L('后台管理模块文件 %s 不存在','Controller/App',null,str_replace(G::tidyPath(WINDSFORCE_PATH),'{WINDSFORCE_PATH}',G::tidyPath($sControllerPath))));
			}
		}
	}

	public function bEdit_(){
		if(!Dyhb::C('APP_DEVELOP')){
			$this->E(Dyhb::L('应用开发尚未开启，请打开配置文件设置 APP_DEVELOP 的值为1','Controller/App'));
		}
	}

	public function bAdd_(){
		$this->bEdit_();
	}

	public function disable(){
		$this->change_status_('status',0,'app');
	}

	public function enable(){
		$this->change_status_('status',1,'app');
	}

	public function export(){
		$nAppId=intval(G::getGpc('id','G'));

		if(empty($nAppId)){
			$this->E(Dyhb::L('你没有指定待设置的应用','Controller/App'));
		}else{
			$arrApp=AppModel::F('app_id=?',$nAppId)->asArray()->getOne();

			if(empty($arrApp['app_id'])){
				$this->E(Dyhb::L('你指定待设置的应用不存在','Controller/App'));
			}
			unset($arrApp['app_id']);
			unset($arrApp['app_status']);

			$arrAppData=array();
			$arrAppData['title']='NeedForBug App';
			$arrAppData['version']=WINDSFORCE_SERVER_VERSION;
			$arrAppData['time']=WINDSFORCE_SERVER_RELEASE;
			$arrAppData['copyright']='Dianniu';

			foreach($arrApp as $key=>$value){
				$arrAppData['data'][str_replace('app_','',$key)]=$value;
			}

			$sName='APP_'.$arrApp['app_identifier'].'_'.date('Y_m_d_H_i_s',CURRENT_TIMESTAMP).'.xml';
			$arrAppData=G::stripslashes($arrAppData);
			$sXmlData=Xml::xmlSerialize($arrAppData,true);

			header('Content-type: text/xml');
			header('Content-Disposition: attachment; filename="'.$sName.'"');

			exit($sXmlData);
		}
	}

	public function nav(){
		$nId=intval(G::getGpc('id'));
		
		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定待设置的应用','Controller/App'));
		}else{
			$oApp=AppModel::F('app_id=?',$nId)->query();
			
			if(empty($oApp['app_id'])){
				$this->E(Dyhb::L('你指定待设置的应用不存在','Controller/App'));
			}

			// 判断菜单是否已经存在
			$oTryNav=NavModel::F('nav_identifier=?','app_'.$oApp['app_identifier'])->getOne();
			if(!empty($oTryNav['nav_id'])){
				$this->E(Dyhb::L('菜单已经存在','Controller/App'));
			}

			// 将菜单数据写入
			$oNav=new NavModel();
			$oNav->nav_title=$oApp['app_identifier'];
			$oNav->nav_name=$oApp['app_name'];
			$oNav->nav_url=$oApp['app_identifier'].'://public/index';
			$oNav->nav_identifier='app_'.$oApp['app_identifier'];
			$oNav->save(0);

			if($oNav->isError()){
				$this->E($oNav->getErrorMessage());
			}else{
				$this->updatecachenav();
				$this->S(Dyhb::L('菜单写入成功','Controller/App'));
			}
		}
	}

	public function unnav(){
		$nId=intval(G::getGpc('id'));
		
		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定待设置的应用','Controller/App'));
		}else{
			$oApp=AppModel::F('app_id=?',$nId)->query();
			
			if(empty($oApp['app_id'])){
				$this->E(Dyhb::L('你指定待设置的应用不存在','Controller/App'));
			}

			// 判断菜单是否已经存在
			$oTryNav=NavModel::F('nav_identifier=?','app_'.$oApp['app_identifier'])->getOne();
			if(empty($oTryNav['nav_id'])){
				$this->E(Dyhb::L('菜单已经被取消','Controller/App'));
			}else{
				$oTryNav->destroy();
			}

			$this->updatecachenav();
			
			$this->S(Dyhb::L('菜单取消成功','Controller/App'));
		}
	}

	public function app_nav_exists($nAppId){
		$nAppId=intval($nAppId);

		if(empty($nAppId)){
			return false;
		}else{
			$oApp=AppModel::F('app_id=?',$nAppId)->query();
			
			if(empty($oApp['app_id'])){
				return false;
			}

			// 判断菜单是否已经存在
			$oTryNav=NavModel::F('nav_identifier=?','app_'.$oApp['app_identifier'])->getOne();
			if(!empty($oTryNav['nav_id'])){
				return true;
			}

			return false;
		}
	}

	protected function updatecachenav(){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache("nav");
	}
	
}
