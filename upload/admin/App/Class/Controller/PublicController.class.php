<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Public控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入Home模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/home/App/Class/Model');

/** 定义Home的语言包 */
define('__APPHOME_COMMON_LANG__',WINDSFORCE_PATH.'/app/home/App/Lang/Admin');

/** 导入杂项函数 */
require(Core_Extend::includeFile('function/Misc_Extend'));

// 导入社会化登录组件
Dyhb::import(WINDSFORCE_PATH.'/source/extension/socialization');

class PublicController extends InitController{

	public function is_login(){
		if($GLOBALS['___login___']===false){
			UserModel::M()->clearThisCookie();// 清理COOKIE
			$this->assign('__JumpUrl__',Dyhb::U('public/login'));
			$this->E(Dyhb::L('你没有登录','Controller'));
		}
	}

	public function fmain(){
		$this->is_login();

		// 系统统计信息
		Core_Extend::loadCache('site');
		$arrStaticInfo=array(
			array(Dyhb::L('用户数量','Controller'),$GLOBALS['_cache_']['site']['user'],Core_Extend::windsforceOuter('app=home&c=stat&a=userlist')),
			array(Dyhb::L('新注册用户数量','Controller'),$GLOBALS['_option_']['todayusernum'],Core_Extend::windsforceOuter('app=home&c=stat&a=newuser')),
			array(Dyhb::L('应用数量','Controller'),$GLOBALS['_cache_']['site']['app'],Core_Extend::windsforceOuter('app=home&c=apps&a=index')),
			array(Dyhb::L('新鲜事数量','Controller'),$GLOBALS['_cache_']['site']['homefresh'],Core_Extend::windsforceOuter('app=home&c=stat&a=explore')),
			array(Dyhb::L('评论数量','Controller'),$GLOBALS['_cache_']['site']['homefreshcomment'],Core_Extend::windsforceOuter('app=home&c=stat&a=explore')),
			array(Dyhb::L('附件','Controller'),$GLOBALS['_cache_']['site']['attachment'],Core_Extend::windsforceOuter('app=home&c=attachment&a=index')),
		);
		$this->assign('arrStaticInfo',$arrStaticInfo);

		// 服务器信息监测
		$oDb=Db::RUN();
		$arrInfo=array(
			Dyhb::L('操作系统','Controller')=>PHP_OS,
			Dyhb::L('运行环境','Controller')=>$_SERVER["SERVER_SOFTWARE"],
			Dyhb::L('PHP运行方式','Controller')=>php_sapi_name(),
			Dyhb::L('数据库类型','Controller')=>$GLOBALS['_commonConfig_']['DB_TYPE'],
			Dyhb::L('数据库版本','Controller')=>$oDb->getConnect()->getVersion(),
			Dyhb::L('上传附件限制','Controller')=>ini_get('upload_max_filesize'),
			Dyhb::L('执行时间限制','Controller')=>ini_get('max_execution_time').' Secconds',
			Dyhb::L('服务器时间','Controller')=>date('Y-n-j H:i:s'),
			Dyhb::L('北京时间','Controller')=>gmdate('Y-n-j H:i:s',time()+8*3600),
			Dyhb::L('服务器域名/IP','Controller')=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
			Dyhb::L('剩余空间','Controller')=>round((@disk_free_space(".")/(1024*1024)),2).'M',
			'register_globals'=>get_cfg_var("register_globals")=="1"?"ON":"OFF",
			'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
			'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
		);
		$this->assign('arrInfo',$arrInfo);

		// 系统文件权限检查
		$arrTestDirs=(array)(include WINDSFORCE_PATH.'/source/common/Cache.php');
		$this->assign('arrTestDirs',$arrTestDirs);

		// 程序信息
		$arrVersionInfo=array(
			'WindsForce '.Dyhb::L('程序版本','Controller')=>"WindsForce " .WINDSFORCE_SERVER_VERSION. "  Release ".
			WINDSFORCE_SERVER_RELEASE.(WINDSFORCE_SERVER_BUG?' Bug-'.WINDSFORCE_SERVER_BUG:'')." [ <a href=\"http://windsforce.com\" target=\"_blank\">".
			Dyhb::L('查看最新版本','Controller')."</a>  <span id=\"newest_version\">".Dyhb::L('读取中','Controller')."...</span> ]&nbsp;"."<a href=\"http://windsforce.com\" target=\"_blank\">".
			Dyhb::L('专业支持与服务','Controller')."</a>",
			'DoYouHaoBaby'.Dyhb::L('版本','Controller')=>DYHB_VERSION.
			' [ <a href="http://bbs.doyouhaobaby.net" target="_blank">'.Dyhb::L('查看最新版本','Controller').'</a> <span id="newest_frameworkversion">'.Dyhb::L('读取中','Controller').'...</span> ] &nbsp;'.
			Dyhb::L('DoYouHaoBaby 是一款性能卓越的PHP 开发框架','Controller').' <img src="'.__FRAMEWORK__.'/dyhb-powered.png" />',
		);
		$this->assign('arrVersionInfo',$arrVersionInfo);

		// 版权信息
		if(is_file(APP_PATH."/App/Lang/".LANG_NAME."/LICENSE.txt")){
			$sCopyTxt=nl2br(file_get_contents(APP_PATH."/App/Lang/".LANG_NAME."/LICENSE.txt"));
		}else{
			$sCopyTxt=nl2br(file_get_contents(APP_PATH."/LICENSE.txt"));
		}
		$this->assign('sCopyTxt',$sCopyTxt);

		// 提示消息
		$arrTipsTxt=array();
		if(is_file(APP_PATH."/App/Lang/".LANG_NAME."/Tips.txt")){
			$tipsTxt=nl2br(file_get_contents(APP_PATH."/App/Lang/".LANG_NAME."/Tips.txt"));
		}else{
			$tipsTxt=nl2br(file_get_contents(APP_PATH."/App/Lang/Tips.txt"));
		}
		$tipsTxt=explode("\r\n",$tipsTxt);
		foreach($tipsTxt as $sValue){
			if(strlen($sValue)==6 || strpos($sValue,'###')===0){
				continue;
			}
			$nValuePos=strpos($sValue,',');
			if($nValuePos<=4){
				$sValue=G::subString($sValue,$nValuePos);
				$sValue=G::subString($sValue,0,-6);
				$sValue=trim($sValue,',');
			}
			$arrTipsTxt[]=$sValue;
		}

		$nTips=mt_rand(0,count($arrTipsTxt)-1);
		$tipsTxt=$arrTipsTxt[$nTips];
		$this->assign('sTipsTxt',$tipsTxt);
		
		// 升级服务端信息
		$sUpdateUrl='http://doyouhaobaby.net/index.php?app=service&c=update&a=index&version='.urlencode(WINDSFORCE_SERVER_VERSION).
			'&release='.urlencode(WINDSFORCE_SERVER_RELEASE).'&bug='.urlencode(WINDSFORCE_SERVER_BUG).'&hostname='.
			urlencode($_SERVER['HTTP_HOST']).'&url='.urlencode($GLOBALS['_option_']['site_name']);
		$this->assign('sUpdateUrl',$sUpdateUrl);

		// 后台在线用户
		$arrUserids=array();
		$sUid=Dyhb::C('ADMIN_USERID');

		$arrSessions=SessionModel::F()->where(array('user_id'=>array('in',$sUid)))->getAll();
		if(is_array($arrSessions)){
			foreach($arrSessions as $oSession){
				$arrUserids[]=$oSession['user_id'];
			}
		}

		$arrUsers=UserModel::F()->where(array('user_id'=>array('in',$arrUserids)))->order('user_id DESC')->getAll();
		$this->assign('arrUsers',$arrUsers);

		// 待处理事情
		$arrTodos=array();
		$nTotalAppeal=AppealModel::F()->where(array('appeal_progress'))->all()->getCounts();

		$arrTodos=array(
			array($nTotalAppeal,Dyhb::L('待申诉用户','Controller'),Dyhb::U('appeal/index?type=0')),
		);
		$this->assign('arrTodos',$arrTodos);

		$this->display();
	}

	public function login(){
		$sReferer=trim(G::getGpc('referer'));
		$nRbac=intval(G::getGpc('rbac','G'));
		
		$arrUserData=$GLOBALS['___login___'];

		if($arrUserData!==false){
			UserModel::M()->replaceSession($arrUserData['session_hash'],$arrUserData['user_id'],isset($arrUserData['session_auth_key'])?$arrUserData['session_auth_key']:'');
			UserModel::M()->logout();

			$GLOBALS['___login___']=false;
		}

		$this->assign('sReferer',$sReferer);
		$this->assign('nRbac',$nRbac);
		
		$this->display();
	}

	public function fheader(){
		$this->is_login();

		$arrUserData=$GLOBALS['___login___'];
		$sUserName=isset($arrUserData['user_nikename']) && $arrUserData['user_nikename']?$arrUserData['user_nikename']:$arrUserData['user_name'];
		$arrMenuList=UserModel::M()->getTopMenuList();
		$this->assign('sUserName',$sUserName);
		$this->assign('arrListMenu',$arrMenuList);

		$this->display();
	}

	public function index($sModel=null,$bDisplay=true){
		$this->is_login();
		G::urlGoto(__APP__);
	}

	public function check_login(){
		$this->check_seccode(true);
		$sUserName=G::getGpc('user_name','P');
		$sPassword=G::getGpc('user_password','P');

		if(empty($sUserName)){
			$this->E(Dyhb::L('帐号或者E-mail不能为空','Controller'));
		}elseif(empty($sPassword)){
			$this->E(Dyhb::L('密码不能为空','Controller'));
		}

		Check::RUN();
		if(Check::C($sUserName,'email')){
			$bEmail=true;
			unset($_POST['user_name']);
		}else{
			$bEmail=false;
		}

		$oUserModel=Dyhb::instance('UserModel');
		$oUserModel->checkLoginCommon($sUserName,$sPassword,$bEmail);
		if($oUserModel->isError()){
			$this->E($oUserModel->getErrorMessage());
		}else{
			$sUrl=Dyhb::U('index/index');
			$this->A(array('url'=>$sUrl),Dyhb::L('Hello %s,你成功登录','Controller',null,$sUserName),1);
		}
	}

	public function password(){
		$this->is_login();

		$arrUserData=$GLOBALS['___login___'];
		$this->assign('nUserId',$arrUserData['user_id']);

		$this->display();
	}

	public function change_pass(){
		$this->is_login();

		$this->check_seccode(true);

		$sPassword=G::getGpc('user_password','P');
		$sNewPassword=G::getGpc('new_password','P');
		$sOldPassword=G::getGpc('old_password','P');

		$oUserModel=Dyhb::instance('UserModel');
		$oUserModel->changePassword($sPassword,$sNewPassword,$sOldPassword);
		if($oUserModel->isError()){
			$this->E($oUserModel->getErrorMessage());
		}else{
			$this->S(Dyhb::L('密码修改成功，你需要重新登录','Controller'));
		}
	}

	public function information(){
		$this->is_login();

		$arrUserData=$GLOBALS['___login___'];
		$oUserInfo=UserModel::F()->getByuser_id($arrUserData['user_id']);
		$this->assign('oUserInfo',$oUserInfo);

		$this->display();
	}

	public function change_info(){
		$this->is_login();

		$this->check_seccode(true);

		$nUserId=G::getGpc('user_id','P');
		$oUser=UserModel::F('user_id=?',$nUserId)->query();
		$oUser->save(0,'update');
		if($oUser->isError()){
			$this->E($oUser->getErrorMessage());
		}else{
			$this->S(Dyhb::L('修改用户资料成功','Controller'));
		}
	}

	public function logout(){
		if(UserModel::M()->isLogin()){
			$arrUserData=$GLOBALS['___login___'];
			if(!isset($arrUserData['session_auth_key'])){
				$arrUserData['session_auth_key']='';
			}
			UserModel::M()->replaceSession($arrUserData['session_hash'],$arrUserData['user_id'],$arrUserData['session_auth_key']);
			UserModel::M()->logout();

			Core_Extend::clearCookie();
			Socia::clearCookie(true);

			$this->assign("__JumpUrl__",Dyhb::U('public/login'));
			$this->S(Dyhb::L('登出成功','Controller'));
		}else{
			$this->E(Dyhb::L('已经登出','Controller'));
		}
	}

	public function fmenu(){
		$this->is_login();

		$sTag=G::getGpc('tag');
		if($sTag===null){
			$sTag='';

			Core_Extend::loadCache('adminctrlmenu');
			$this->assign('arrAdminctrlmenus',$GLOBALS['_cache_']['adminctrlmenu']);
		}

		$arrMenuList=UserModel::M()->getMenuList();
		$this->assign('sMenuTag',$sTag);
		$this->assign('arrMenuList',$arrMenuList);

		$this->display();
	}

	public function program_update(){
		$sUpdateUrl='http://doyouhaobaby.net/index.php?app=service&c=update&a=index&version='.urlencode(WINDSFORCE_SERVER_VERSION).
			'&release='.urlencode(WINDSFORCE_SERVER_RELEASE).'&bug='.urlencode(WINDSFORCE_SERVER_BUG).'&hostname='.
			urlencode($_SERVER['HTTP_HOST']).'&url='.urlencode($GLOBALS['_option_']['site_name']).'&infolist=1';
		$this->assign('sUpdateUrl',$sUpdateUrl);

		$arrOptionData=$GLOBALS['_option_'];
		$this->assign('arrOptions',$arrOptionData);

		$this->display();
	}
	
	public function programeupdate_option(){
		$oOptionController=new OptionController();

		$oOptionController->update_option();
	}

	public function close_updateinfo(){
		$oOptionModel=OptionModel::F('option_name=?','programeupdate_on')->getOne();
		$oOptionModel->option_value=0;
		$oOptionModel->save(0,'update');
		
		if($oOptionModel->isError()){
			$this->E($oOptionModel->getErrorMessage());
		}

		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('option');

		$this->S(Dyhb::L('升级提醒信息成功关闭','Controller'));
	}

	public function profile(){
		$arrUserData=$GLOBALS['___login___'];
		$sUserName=isset($arrUserData['user_nikename']) && $arrUserData['user_nikename']?$arrUserData['user_nikename']:$arrUserData['user_name'];
		$this->assign('sUserName',$sUserName);

		$this->display();
	}

}
