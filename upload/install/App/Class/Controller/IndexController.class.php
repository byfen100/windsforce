<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   WindsForce 安装程序($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 配置WindsForce默认数据库名字 */
define('WINDSFORCE_DATABASE','windsforce_v'.WINDSFORCE_SERVER_RELEASE);

class IndexController extends Controller{

	protected $_sLockfile='';

	public function init__(){
		parent::init__();

		if(ACTION_NAME!=='success'){
			$this->check();
		}
	}

	public function check(){
		$sInstallLockfile=WINDSFORCE_PATH.'/data/Install.lock.php';
		$sUpdateLockfile=WINDSFORCE_PATH.'/data/Update.lock.php';

		if(is_file($sInstallLockfile) && is_file($sUpdateLockfile)){
			$this->E(Dyhb::L("程序已经锁定，既不需要安装也不需要升级，如有需要请删除安装锁定文件 %s 或者升级锁定文件 %s",'Controller/Common',null,$sInstallLockfile,$sUpdateLockfile));
		}
	}

	public function check_install(){
		$this->_sLockfile=WINDSFORCE_PATH.'/data/Install.lock.php';

		if(is_file($this->_sLockfile)){
			$this->E(Dyhb::L("程序已运行安装，如果你确定要重新安装，请先从FTP中删除 %s",'Controller/Install',null,str_replace(G::tidyPath(WINDSFORCE_PATH),'{WINDSFORCE_PATH}',G::tidyPath($this->_sLockfile))));
		}
	}

	public function index(){
		$this->assign('arrInstallLangs',G::listDir(APP_PATH.'/App/Lang'));
		$this->display('step+language');
	}

	public function select(){
		$this->display('step+select');
	}

	public function get_progress(){
		if(ACTION_NAME==='step1'){
			return 20;
		}elseif(ACTION_NAME==='step2'){
			return 40;
		}elseif(ACTION_NAME==='step3'){
			return 60;
		}elseif(ACTION_NAME==='install'){
			return 80;
		}elseif(ACTION_NAME==='success'){
			return 100;
		}

		return 0;
	}

	public function step1(){
		$this->check_install();

		// 版权信息
		if(is_file(APP_PATH."/App/Lang/".LANG_NAME."/LICENSE.dyhb")){
			$sCopyTxt=nl2br(file_get_contents(APP_PATH."/App/Lang/".LANG_NAME."/LICENSE.dyhb"));
		}else{
			$sCopyTxt=nl2br(file_get_contents(APP_PATH."/App/Lang/LICENSE.dyhb"));
		}
		$this->assign('sCopyTxt',$sCopyTxt);

		$this->display('install+step1');
	}

	public function step2(){
		// 取得服务器相关信息
		$arrInfo=array();

		$arrInfo['phpv']=phpversion();
		$arrInfo['sp_os']=PHP_OS;
		
		$arrInfo['sp_gd']=Install_Extend::gdVersion();
		
		$arrInfo['sp_server']=$_SERVER['SERVER_SOFTWARE'];
		$arrInfo['sp_host']=(empty($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_HOST']:$_SERVER['REMOTE_ADDR']);
		$arrInfo['sp_name']=$_SERVER['SERVER_NAME'];
		$arrInfo['sp_max_execution_time']=ini_get('max_execution_time');
		
		$arrInfo['sp_allow_reference']=(ini_get('allow_call_time_pass_reference')?'<font color=green>[√]On</font>':'<font color=red>[×]Off</font>');
		$arrInfo['sp_allow_url_fopen']=(ini_get('allow_url_fopen')?'<font color=green>[√]On</font>':'<font color=red>[×]Off</font>');
		
		$arrInfo['sp_safe_mode']=(ini_get('safe_mode')?'<font color=red>[×]On</font>':'<font color=green>[√]Off</font>');
		$arrInfo['sp_gd']=($arrInfo['sp_gd']>0?'<font color=green>[√]On</font>':'<font color=red>[×]Off</font>');
		
		$arrInfo['sp_mysql']=(function_exists('mysql_connect')?'<font color=green>[√]On</font>':'<font color=red>[×]Off</font>');
		if($arrInfo['sp_mysql']=='<font color=red>[×]Off</font>'){
			$bSpMysqlErr=TRUE;
		}else{
			$bSpMysqlErr=FALSE;
		}

		// 系统安装权限检查
		$arrSpTestDirs=(array)(include WINDSFORCE_PATH.'/source/common/Cache.php');

		$this->assign('arrInfo',$arrInfo);
		$this->assign('bSpMysqlErr',$bSpMysqlErr);
		$this->assign('arrSpTestDirs',$arrSpTestDirs);

		$this->display('install+step2');
	}

	public function step3(){
		$this->check_install();

		if(!empty($_SERVER['HTTP_HOST'])){
			$sBaseurl='http://'.$_SERVER['HTTP_HOST'];
		}else{
			$sBaseurl="http://".$_SERVER['SERVER_NAME'];
		}

		$sBaseurl=$sBaseurl.__ROOT__;

		$arrApps=G::listDir(APP_PATH.'/Static/Sql/Install/Zh-cn/App');

		$this->assign('sBasepath',$sBaseurl);
		$this->assign('sBaseurl',$sBaseurl);
		$this->assign('arrApps',$arrApps);

		$this->display('install+step3');
	}

	public function install(){
		global $hConn,$sSql4Tmp,$sDbprefix,$nMysqlVersion;

		$this->check_install();

		// 获取表单数据
		$sDbhost=trim(G::getGpc('dbhost'));
		$sDbuser=trim(G::getGpc('dbuser'));
		$sDbpwd=trim(G::getGpc('dbpwd'));
		$sDbname=trim(G::getGpc('dbname'));
		$sDbprefix=trim(G::getGpc('dbprefix'));
		$sAdminuser=trim(G::getGpc('adminuser'));
		$sAdminpwd=trim(G::getGpc('adminpwd'));
		$sCookieprefix=trim(G::getGpc('cookieprefix'));
		$sRbacprefix=trim(G::getGpc('rbacprefix'));

		// 验证表单
		if(empty($sAdminuser)){
			$this->E(Dyhb::L('管理员帐号不能为空','Controller/Install'));
		}

		if(!preg_match('/^[a-z0-9\-\_]*[a-z\-_]+[a-z0-9\-\_]*$/i',$sAdminuser)){
			$this->E(Dyhb::L('管理员帐号只能是由英文,字母和下划线组成','Controller/Install'));
		}

		if(empty($sAdminpwd)){
			$this->E(Dyhb::L('管理员密码不能为空','Controller/Install'));
		}

		if(empty($sRbacprefix)){
			$this->E(Dyhb::L('Rbac前缀不能为空','Controller/Install'));
		}

		if(empty($sCookieprefix)){
			$this->E(Dyhb::L('Cookie前缀不能为空','Controller/Install'));
		}

		if((!$hConn=@mysql_connect($sDbhost,$sDbuser,$sDbpwd))){
			$this->E(Dyhb::L('数据库服务器或登录密码无效','Controller/Install').",".Dyhb::L('无法连接数据库，请重新设定','Controller/Install'));
		}

		Install_Extend::queryString("CREATE DATABASE IF NOT EXISTS `".$sDbname."`;");

		if(!mysql_select_db($sDbname)){
			$this->E(Dyhb::L('选择数据库失败，可能是你没权限，请预先创建一个数据库','Controller/Install'));
		}

		// 取得数据库版本
		$sRs=Install_Extend::queryString("SELECT VERSION();");
		$arrRow=mysql_fetch_array($sRs);
		$arrMysqlVersions=explode('.',trim($arrRow[0]));
		$nMysqlVersion=$arrMysqlVersions[0].".".$arrMysqlVersions[1];

		Install_Extend::queryString("SET NAMES 'UTF8',character_set_client=binary,sql_mode='';");

		// 写入配置文件
		$arrConfig=(array)(include WINDSFORCE_PATH.'/config/ConfigDefault.inc.php');
		$arrConfig['DB_HOST']=$sDbhost;
		$arrConfig['DB_USER']=$sDbuser;
		$arrConfig['DB_PASSWORD']=$sDbpwd;
		$arrConfig['DB_NAME']=$sDbname;
		$arrConfig['DB_PREFIX']=$sDbprefix;
		$arrConfig['RBAC_DATA_PREFIX']=$sRbacprefix;
		$arrConfig['COOKIE_PREFIX']=$sCookieprefix;
		
		if(!file_put_contents(WINDSFORCE_PATH.'/config/Config.inc.php',
			"<?php\n /* DoYouHaoBaby Framework Config File,Do not to modify this file! */ \n return ".
			var_export($arrConfig,true).
			"\n?>")
		){
			$this->E(Dyhb::L('写入配置失败，请检查 %s目录是否可写入','Controller/Install',null,WINDSFORCE_PATH.'/Config'));
		}

		// 输出消息框
		$this->display('install+message');

		// 防止乱码
		$sSql4Tmp='';
		if($nMysqlVersion>=4.1){
			$sSql4Tmp="ENGINE=MyISAM DEFAULT CHARSET=UTF8";
		}
		
		// 创建系统表
		Install_Extend::showJavascriptMessage('<h3>'.Dyhb::L('创建系统数据库表','Controller/Install').'</h3>');
		Install_Extend::importTable(APP_PATH.'/Static/Sql/Install/windsforce.table.sql');
		Install_Extend::showJavascriptMessage(' ');

		$sLangCookieName=$GLOBALS['_commonConfig_']['COOKIE_LANG_TEMPLATE_INCLUDE_APPNAME']===true?APP_NAME.'_language':'language';
		$sWindsForceDatadir=APP_PATH.'/Static/Sql/Install';

		// 执行系统初始化数据
		$sWindsForceDatapath=$sWindsForceDatadir.'/'.ucfirst(Dyhb::cookie($sLangCookieName)).'/windsforce.data.sql';
		if(!is_file($sWindsForceDatapath)){
			$sWindsForceDatapath=$sWindsForceDatadir.'/Zh-cn/windsforce.data.sql';
		}
		Install_Extend::showJavascriptMessage('<h3>'.Dyhb::L('初始化系统数据库数据','Controller/Install').'</h3>');
		Install_Extend::runQuery($sWindsForceDatapath);
		Install_Extend::showJavascriptMessage(' ');

		// 导入地理数据
		Install_Extend::showJavascriptMessage('<h3>'.Dyhb::L('导入地理数据库数据','Controller/Install').'</h3>');
		for($nI=1;$nI<=6;$nI++){
			Install_Extend::showJavascriptMessage(Dyhb::L('导入地理数据库数据','Controller/Install').$nI);
			Install_Extend::runQuery($sWindsForceDatadir.'/district/'.$nI.'.sql',false);
		}
		Install_Extend::showJavascriptMessage(' ');

		// 安装系统预置应用
		Install_Extend::showJavascriptMessage('<h3>'.Dyhb::L('安装系统预置应用','Controller/Install').'</h3>');
		
		$arrApps=G::getGpc('app','P');
		if(empty($arrApps)){
			Install_Extend::showJavascriptMessage(Dyhb::L('没有发现需要安装的应用','Controller/Install'));
			Install_Extend::showJavascriptMessage(' ');
		}else{
			foreach($arrApps as $sApp){
				Install_Extend::showJavascriptMessage(Dyhb::L('创建应用 %s 的数据库表','Controller/Install',null,$sApp));
				Install_Extend::importTable($sWindsForceDatadir.'/app/'.$sApp.'/windsforce.table.sql');
				Install_Extend::showJavascriptMessage(' ');

				$sWindsForceAppDatapath=$sWindsForceDatadir.'/'.ucfirst(Dyhb::cookie($sLangCookieName)).'/app/'.$sApp.'/windsforce.data.sql';
				if(!is_file($sWindsForceAppDatapath)){
					$sWindsForceAppDatapath=$sWindsForceDatadir.'/Zh-cn/app/'.$sApp.'/windsforce.data.sql';
				}
				Install_Extend::showJavascriptMessage(Dyhb::L('导入应用 %s 的数据库数据','Controller/Install',null,$sApp));
				Install_Extend::runQuery($sWindsForceAppDatapath);
				Install_Extend::showJavascriptMessage(' ');
			}
		}

		// 初始化安装程序设置
		Install_Extend::showJavascriptMessage('<h3>'.Dyhb::L('初始化安装程序设置','Controller/Install').'</h3>');
		
		Install_Extend::queryString("Update `{$sDbprefix}option` set option_value='".trim(G::getGpc('baseurl'))."' where option_name='site_url';");
		Install_Extend::showJavascriptMessage(Dyhb::L('写入社区地址','Controller/Install').' '.trim(G::getGpc('baseurl')).' ... '.Dyhb::L('成功','Controller/Common'));

		Install_Extend::queryString("Update `{$sDbprefix}option` set option_value='".trim(G::getGpc('webname'))."' where option_name='site_name';");
		Install_Extend::showJavascriptMessage(Dyhb::L('写入社区名称','Controller/Install').' '.trim(G::getGpc('webname')).' ... '.Dyhb::L('成功','Controller/Common'));

		Install_Extend::queryString("Update `{$sDbprefix}option` set option_value='".trim(G::getGpc('adminmail'))."' where option_name='admin_email';");
		Install_Extend::showJavascriptMessage(Dyhb::L('写入管理员邮件','Controller/Install').' '.trim(G::getGpc('adminmail')).' ... '.Dyhb::L('成功','Controller/Common'));
		Install_Extend::showJavascriptMessage(' ');

		// 初始化管理员信息
		Install_Extend::showJavascriptMessage('<h3>'.Dyhb::L('初始化管理员信息','Controller/Install').'</h3>');
		
		$sRandom=G::randString(6);
		$sPassword=md5(md5($sAdminpwd).trim($sRandom));
		Install_Extend::queryString("Update `{$sDbprefix}user` set user_name='".$sAdminuser."',user_password='".$sPassword."',user_random='".$sRandom."',user_password='".$sPassword."',user_registerip='".G::getIp()."',user_email='".trim(G::getGpc('adminmail'))."',user_lastloginip='".G::getIp()."' where user_id=1;");
		Install_Extend::showJavascriptMessage(Dyhb::L('初始化超级管理员帐号','Controller/Install').'... '.Dyhb::L('成功','Controller/Common'));
		Install_Extend::showJavascriptMessage(' ');

		// 写入锁定文件
		if(!file_put_contents($this->_sLockfile,'ok')){
			$this->E(Dyhb::L('写入安装锁定文件失败，请检查%s目录是否可写入','Controller/Install',null,WINDSFORCE_PATH.'/data'));
		}
		Install_Extend::showJavascriptMessage(Dyhb::L('写入安装程序锁定文件','Controller/Install').'... '.Dyhb::L('成功','Controller/Common'));
		Install_Extend::showJavascriptMessage(' ');

		// 执行清理
		if(is_dir(WINDSFORCE_PATH.'/data/~runtime')){
			Install_Extend::showJavascriptMessage('<h3>'.Dyhb::L('清理系统缓存目录','Controller/Install').'</h3>');
			Install_Extend::removeDir(WINDSFORCE_PATH.'/data/~runtime');
		}

		// 初始化系统和跳转
		$sInitsystemUrl=trim(G::getGpc('baseurl')).'/index.php?app=home&c=misc&a=init_system';
		
		echo<<<WINDSFORCE
		<script type="text/javascript">
			function setLaststep(){
				setTimeout(function(){
					document.getElementById("laststep").disabled=false;
					window.location=D.U('index/success');
				},1000);
			}
		</script>
		<script type="text/javascript">setTimeout(function(){window.location=window.location=D.U('index/success');},30000);
		</script>
		<iframe src="{$sInitsystemUrl}" style="display:none;" onload="setLaststep()"></iframe>
WINDSFORCE;

		exit();
	}

	public function success(){
		$this->display('install+success');
	}

	public function check_database(){
		$this->check_install();

		header("Pragma:no-cache\r\n");
		header("Cache-Control:no-cache\r\n");
		header("Expires:0\r\n");

		$sDbhost=G::getGpc('dbhost');
		$sDbuser=G::getGpc('dbuser');
		$sDbpwd=G::getGpc('dbpwd');
		$sDbname=G::getGpc('dbname');
		$hConn=@mysql_connect($sDbhost,$sDbuser,$sDbpwd);

		if($hConn){
			if(empty($sDbname)){
				$this->S("<font color='green'>".Dyhb::L('数据库连接成功','Controller/Install')."</font>",0);
			}else{
				if(mysql_select_db($sDbname,$hConn)){
					$this->E("<font color='red'>".Dyhb::L('数据库已经存在,系统将覆盖数据库','Controller/Install')."</font>",0);
				}else{
					$this->S("<font color='green'>".Dyhb::L('数据库不存在,系统将自动创建','Controller/Install')."</font>",0);
				}
			}
		}else{
			$this->E("<font color='red'>".Dyhb::L('数据库连接失败','Controller/Install')."</font>",0);
		}

		@mysql_close($hConn);

		exit();
	}

}
