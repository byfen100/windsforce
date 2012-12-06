<?php
/**

 //  [Websetup!] 图像界面工具
 //  +---------------------------------------------------------------------
 //
 //  “Copyright”
 //  +---------------------------------------------------------------------
 //  | (C) 2010 - 2099 http://doyouhaobaby.net All rights reserved.
 //  | This is not a free software, use is subject to license terms
 //  +---------------------------------------------------------------------
 //
 //  “About This File”
 //  +---------------------------------------------------------------------
 //  | websetup Index控制器
 //  +---------------------------------------------------------------------

*/

!defined('DYHB_PATH') && exit;

class IndexController extends InitController{

	 public function index(){
		$sDyhbWebsetupApp=Dyhb::cookie('dyhb_websetup_app');

		if(!is_file($sDyhbWebsetupApp."/App/Config/Config.php")){
			$this->E(Dyhb::L("项目%s的配置文件%s不存在！",'app',null,$sDyhbWebsetupApp,$sDyhbWebsetupApp.'/App/Config/Config.php'));
		}

		$arrMyConfig=(array)include($sDyhbWebsetupApp.'/App/Config/Config.php');
		$arrConfigs=array(
			'APP_PATH'=>$sDyhbWebsetupApp,
			'MODEL_DIR'=>$sDyhbWebsetupApp.'/App/Class/Model',
			'ACTION_DIR'=>$sDyhbWebsetupApp.'/App/Class/Controller',
			Dyhb::L('自定义配置')=>G::dump($arrMyConfig,false),
		);

		$this->assign('arrConfig',$arrConfigs);
		$this->assign('sHelp',Dyhb::L('在开始使用 WebSetup 之前，请务必仔细核对本页列出的应用程序信息，例如应用程序所在路径等等信息.只有当这些信息正确无误时，WebSetup 才能够正常工作.'));

		$this->display();
	 }

	 public function createwebsetupapp(){
		$sDyhbWebsetupApp=G::getGpc('dyhb_websetup_app','P');

		if(!is_dir($sDyhbWebsetupApp)){
			$this->E(Dyhb::L('应用程序目录不存在！'));
		}

		if(!is_dir($sDyhbWebsetupApp.'/App/~Runtime')){
			$this->E(Dyhb::L('我们没有发现应用程序中的~Runtimes文件夹，也许你的应用程序只是一个空的文件夹。或者尚未创建！'));
		}

		Dyhb::cookie('dyhb_websetup_app',$sDyhbWebsetupApp);
		if(!file_put_contents(WEBSETUP_PATH.'/~Data/websetup/AppPathCookie.php',$sDyhbWebsetupApp)){
			Dyhb::E(Dyhb::L('文件%s写入失败，Linux请确认其权限为0777.','app',null,WEBSETUP_PATH.'/~Data/websetup/AppPathCookie.php'));
		}

		if(is_file(APP_RUNTIME_PATH.'/Config.php')&& !unlink(APP_RUNTIME_PATH.'/Config.php')){
			Dyhb::E(Dyhb::L('删除文件%s失败，Linux请确认其权限为0777.','app',null,APP_RUNTIME_PATH.'/Config.php'));
		}

		$this->assign('__JumpUrl__',Dyhb::U('index/index'));

		$this->S(Dyhb::L('添加项目%s成功！','app',null,$sDyhbWebsetupApp));
	}

	public function config(){
		$this->assign('sHelp',Dyhb::L('你可以在这里设置应用程序初始化相关配置，比如数据库连接，URL模式等等。<br/>也许你感到设置项太多，无从下手，通常你只需配置数据库名字(DB_NAME)、数据库用户名(DB_USER)、 数据库密码（DB_PASSWORD）以及为了获得良好的URL体验的URL模式设置。'));

		$sDyhbWebsetupApp=Dyhb::cookie('dyhb_websetup_app');
		if(!is_dir($sDyhbWebsetupApp)){
			$this->E(Dyhb::L('应用程序目录不存在！'));
		}

		$arrDefaultConfig=(array)include(DYHB_PATH.'/Config_/DefaultConfig.inc.php');  // 系统惯性配置
		$arrCustomConfig=array();
		if(is_file($sDyhbWebsetupApp."/App/Config/Config.php")){// 自定义配置
			$arrCustomConfig =(array)include($sDyhbWebsetupApp.'/App/Config/Config.php');
		}

		$arrConfigs=$this->arrayKeyToCase(array_merge($arrDefaultConfig,$arrCustomConfig));
		$this->assign('arrConfigs',$arrConfigs);
		unset($arrDefaultConfig,$arrCustomConfig,$arrConfigs);

		$arrDbs=array();
		$arrDbFiles=includeDirPhpfile(DYHB_PATH.'/Db/DbFactory/DbFactoryDriver');
		if(is_array($arrDbFiles)){
			foreach($arrDbFiles as $sVal){
				$sVal= basename($sVal);
				$sVal=str_replace('DbFactory','',$sVal);
				$sVal=str_replace('.class.php','',$sVal);
				if($sVal!='index.php'){
					$arrDbs[]=$sVal;
				}
			}
		}
		$this->assign('arrDbs',$arrDbs);
		unset($arrDbs);

		$arrAppLangs=G::listDir(APP_PATH.'/App/Lang');
		$this->assign('arrAppLangs',$arrAppLangs);
		unset($arrAppLangs);

		$arrAppTemplates=G::listDir(APP_PATH.'/Theme');
		$this->assign('arrAppTemplates',$arrAppTemplates);
		unset($arrAppTemplates);

		$arrUrlModels=array(
			array(
				'name'=>Dyhb::L('普通模式'),
				'value'=>0,
			),
			array(
				'name'=>Dyhb::L('PATHINFO模式'),
				'value'=>1,
			),
			array(
				'name'=>Dyhb::L('REWRITE模式'),
				'value'=>2,
			),
			array(
				'name'=>Dyhb::L('兼容模式'),
				'value'=>3,
			),
		);
		$this->assign('arrUrlModels',$arrUrlModels);
		unset($arrUrlModels);

		$this->display();
	}

	public function changeconfig(){
		$sDyhbWebsetupApp=Dyhb::cookie('dyhb_websetup_app');

		if(!is_dir($sDyhbWebsetupApp)){
			$this->E(Dyhb::L('应用程序目录不存在！'));
		}

		$arrConfigs=G::getGpc('configs');
		if($arrConfigs['db_host']==''){
			$arrConfigs['db_host']='localhost';
		}

		if($arrConfigs['db_type']==''){
			$arrConfigs['db_type']='mysql';
		}else{
			$arrConfigs['db_type']=strtolower($arrConfigs['db_type']);
		}

		if($arrConfigs['db_user']==''){
			$this->E(Dyhb::L('数据库用户名不能为空！'));
		}
		if($arrConfigs['db_prefix']==''){
			$this->E(Dyhb::L('数据库表前缀不能为空！'));
		}

		if($arrConfigs['db_name']==''){
			$this->E(Dyhb::L('数据库名字不能为空！'));

		}
		$arrDefaultConfigs=(array)include(DYHB_PATH.'/Config_/DefaultConfig.inc.php');

		$arrUserConfigs=array();
		foreach($arrConfigs as $sKey=>$sValue){
			$sPostValue=$sValue;
			if(strtolower($sValue)=='true'){
				$sValue=1;
			}

			if(strtolower($sValue)=='false'){
				$sValue='';
			}

			if($sValue!='' && $sValue!= $arrDefaultConfigs[strtoupper($sKey)]){
				$arrUserConfigs[$sKey]=$sPostValue;
			}
		}

		$sCode=$this->makeConfigCode_($arrUserConfigs);
		$sPath=$sDyhbWebsetupApp."/App/Config/Config.php";

		if(is_file(APP_RUNTIME_PATH.'/Config.php')&& !unlink(APP_RUNTIME_PATH.'/Config.php')){
			Dyhb::E(Dyhb::L('删除文件%s失败，Linux请确认其权限为0777.','app',null,APP_RUNTIME_PATH.'/Config.php'));
		}

	 	if(file_put_contents($sPath,$sCode)){
			$this->S(Dyhb::L('修改配置文件：%s成功！','app',null,$sPath));
		}else{
			$this->E(Dyhb::L('修改配置文件：%s失败！','app',null,$sPath));
		}
	}

	protected function arrayKeyToCase($arrValue,$bUpperCase=FALSE){
		$arrValueTemp=array();

		foreach($arrValue as $sKey=>$item){
			if($bUpperCase===TRUE){
				$sKeyTemp=strtoupper($sKey);
			}else{
				$sKeyTemp=strtolower($sKey);
			}

			$arrValueTemp[$sKeyTemp]=$item;
			if(is_array($item)){
				$arrValueTemp[$sKeyTemp]=self::arrayKeyToCase($item,$bUpperCase);
			}
		}

		return $arrValueTemp;
	}

	private function makeConfigCode_($arrUserConfigs){
		$sStart="<?php
/**

 //  [DoYouHaoBaby!] Init APP - websetup
 //  +---------------------------------------------------------------------
 //
 //  “Copyright”
 //  +---------------------------------------------------------------------
 //  |(C)2010 - 2099 http://doyouhaobaby.net All rights reserved.
 //  | This is not a free software,use is subject to license terms
 //  +---------------------------------------------------------------------
 //
 //  “About This File”
 //  +---------------------------------------------------------------------
 //  | Config 配置文件
 //  +---------------------------------------------------------------------

*/

!defined('DYHB_PATH') && exit;

/**
 * 通过 < Websetup 图形化工具 >自动创建的配置文件
 */
return array(

";
		$sContent='';
		if($arrUserConfigs){
			foreach($arrUserConfigs as $sKey=>$sValue){
				$sBrCode=IS_WIN?"\n":"\r\n";
				$sValueString=(strtolower($sValue)!='false' && strtolower($sValue)!='true' && !is_numeric($sValue))? '\'':'';
;				$sContent.='	\''.strtoupper($sKey).'\'=>'.$sValueString.addslashes($sValue).$sValueString.",".$sBrCode;
			}
		}
		$sEnd=");

";
		return $sStart.$sContent.$sEnd;
	}

	public function unsetwebsetupapp(){
		Dyhb::cookie('dyhb_websetup_app','',-1);
		if(is_file(WEBSETUP_PATH.'/~Data/websetup/AppPathCookie.php') && !unlink(WEBSETUP_PATH.'/~Data/websetup/AppPathCookie.php')){
			Dyhb::E(Dyhb::L('删除文件%s失败，Linux请确认其权限为0777.','app',null,WEBSETUP_PATH.'/~Data/websetup/AppPathCookie.php'));
		}

		if(is_file(APP_RUNTIME_PATH.'/Config.php')&& !unlink(APP_RUNTIME_PATH.'/Config.php')){
			Dyhb::E(Dyhb::L('删除文件%s失败，Linux请确认其权限为0777.','app',null,APP_RUNTIME_PATH.'/Config.php'));
		}

		$this->S(Dyhb::L('项目被成功注销！'));
	}

	public function lock(){
		$sLock="DoYouHaoBaby Framework Websetup Tools Is Locked !";
		$sPath=WEBSETUP_PATH.'/~Data/websetup/WebsetupLock.php';
		if(is_file(WEBSETUP_PATH.'/~Data/websetup/AppPathCookie.php') && !unlink(WEBSETUP_PATH.'/~Data/websetup/AppPathCookie.php')){
			Dyhb::E(Dyhb::L('删除文件%s失败，Linux请确认其权限为0777.','app',null,WEBSETUP_PATH.'/~Data/websetup/AppPathCookie.php'));
		}

	 	if(file_put_contents($sPath,$sLock)){
			$this->S(Dyhb::L('创建锁定文件：%s成功！','app',null,$sPath));
		}else{
			$this->E(Dyhb::L('创建锁定文件：%s失败！','app',null,$sPath));
		 }
	}

	public function thanks(){
		$this->assign('sHelp',Dyhb::L('在此感谢前辈们的无私的奉献。 '));

		$this->display();
	}

}
