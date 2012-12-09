<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   Needforbug 升级程序($)*/

!defined('DYHB_PATH') && exit;

class UpdateController extends Controller{

	protected $_sUpdatefile='';

	public function init__(){
		parent::init__();
	}

	public function check_update(){
		$this->_sUpdatefile=NEEDFORBUG_PATH.'/data/Update.lock.php';

		if(is_file($this->_sUpdatefile)){
			$this->E(Dyhb::L("程序已运行升级，如果你确定要重新升级（可能出现错误），请先从FTP中删除 %s",'Controller/Update',null,str_replace(G::tidyPath(NEEDFORBUG_PATH),'{NEEDFORBUG_PATH}',G::tidyPath($this->_sUpdatefile))));
		}
	}

	public function get_progress(){
		if(ACTION_NAME==='index'){
			return 20;
		}elseif(ACTION_NAME==='step2'){
			return 40;
		}elseif(ACTION_NAME==='step3'){
			return 60;
		}elseif(in_array(ACTION_NAME,array('first','second','three'))){
			return 80;
		}elseif(ACTION_NAME==='success'){
			return 100;
		}

		return 0;
	}

	public function index(){
		$this->check_update();

		$this->display('update+step1');
	}

	public function step2(){
		$oIndexController=new IndexController();
		$oIndexController->step2();
	}

	public function step3(){
		$this->check_update();

		$sConfigfile=NEEDFORBUG_PATH.'/config/Config.inc.php';
		if(is_file($sConfigfile)){
			$arrConfig=(array)(include $sConfigfile);
		}else{
			$this->E(Dyhb::L('数据库连接配置文件 %s 不存在','Controller/Update',null,$sConfigfile));
		}

		$this->assign('arrConfig',$arrConfig);

		$this->display('update+step3');
	}

	public function db_connect(){
		global $hConn,$sSql4Tmp,$sDbprefix,$nMysqlVersion;

		$arrConfig=(array)(include NEEDFORBUG_PATH.'/config/Config.inc.php');
		if(empty($arrConfig['RBAC_DATA_PREFIX'])){
			$this->E(Dyhb::L('Rbac前缀不能为空','Controller/Update'));
		}

		if(empty($arrConfig['COOKIE_PREFIX'])){
			$this->E(Dyhb::L('Cookie前缀不能为空','Controller/Update'));
		}

		if(!$hConn=@mysql_connect($arrConfig['DB_HOST'],$arrConfig['DB_USER'],$arrConfig['DB_PASSWORD'])){
			$this->E(Dyhb::L('数据库服务器或登录密码无效','Controller/Update').",".Dyhb::L('无法连接数据库，请重新设定','Controller/Update'));
		}

		if(!mysql_select_db($arrConfig['DB_NAME'])){
			$this->E(Dyhb::L('选择数据库失败，可能是你没权限，请预先创建一个数据库','Controller/Update'));
		}

		$sRs=Install_Extend::queryString("SELECT VERSION();");
		$arrRow=mysql_fetch_array($sRs);
		$arrMysqlVersions=explode('.',trim($arrRow[0]));
		$nMysqlVersion=$arrMysqlVersions[0].".".$arrMysqlVersions[1];
		Install_Extend::queryString("SET NAMES 'UTF8',character_set_client=binary,sql_mode='';");

		// 前缀
		$sDbprefix=$arrConfig['DB_PREFIX'];
	
		// 防止乱码
		$sSql4Tmp='';
		if($nMysqlVersion>=4.1){
			$sSql4Tmp="ENGINE=MyISAM DEFAULT CHARSET=UTF8";
		}
	}

	public function first(){
		global $hConn,$sSql4Tmp,$sDbprefix,$nMysqlVersion;

		$this->check_update();

		$this->db_connect();

		// 加载升级界面
		$this->assign('sUpdateTitle',Dyhb::L('数据库结构添加与更新','Controller/Update'));
		$this->display('update_message');

		// 开始执行数据库结构升级
		Install_Extend::showJavascriptMessage('<h3>'.Dyhb::L('数据库结构添加与更新','Controller/Update').'</h3>');
		Install_Extend::runQuery(APP_PATH.'/Static/Sql/Update/needforbug.table.sql');

		// 执行结束
		Install_Extend::showJavascriptMessage('');
		Install_Extend::showJavascriptMessage('<h3>'.Dyhb::L('数据库结构添加与更新执行完毕','Controller/Update').'</h3>');
		Install_Extend::showJavascriptMessage('<h3 style="color:red;">'.Dyhb::L('程序将会在3秒后继续执行，请勿关闭窗口','Controller/Update').'</h3>');

		// 系统跳转
		echo<<<NEEDFORBUG
		<script type="text/javascript">
			function setLaststep(){
				setTimeout(function(){
					document.getElementById("laststep").disabled=false;
					window.location=D.U('update/second');
				},3000);
			}
			setLaststep();
		</script>
NEEDFORBUG;

		exit();
	}

	public function second(){
		global $hConn,$sSql4Tmp,$sDbprefix,$nMysqlVersion;

		$this->check_update();

		$this->db_connect();
		
		// 加载升级界面
		$this->assign('sUpdateTitle',Dyhb::L('数据库数据添加与更新','Controller/Update'));
		$this->display('update_message');

		$sLangCookieName=$GLOBALS['_commonConfig_']['COOKIE_LANG_TEMPLATE_INCLUDE_APPNAME']===true?APP_NAME.'_language':'language';
		$sNeedforbugDatadir=APP_PATH.'/Static/Sql/Update';
		
		// 开始写入和更新数据库数据
		Install_Extend::showJavascriptMessage('<h3>'.Dyhb::L('数据库数据添加与更新','Controller/Update').'</h3>');

		$sNeedforbugDatapath=$sNeedforbugDatadir.'/'.ucfirst(Dyhb::cookie($sLangCookieName)).'/needforbug.data.sql';
		if(!is_file($sNeedforbugDatapath)){
			$sNeedforbugDatapath=$sNeedforbugDatadir.'/Zh-cn/needforbug.data.sql';
		}
		Install_Extend::runQuery($sNeedforbugDatapath);

		// 执行结束
		Install_Extend::showJavascriptMessage('');
		Install_Extend::showJavascriptMessage('<h3>'.Dyhb::L('数据库数据添加与更新执行完毕','Controller/Update').'</h3>');
		Install_Extend::showJavascriptMessage('<h3 style="color:red;">'.Dyhb::L('程序将会在3秒后继续执行，请勿关闭窗口','Controller/Update').'</h3>');

		// 系统跳转
		echo<<<NEEDFORBUG
		<script type="text/javascript">
			function setLaststep(){
				setTimeout(function(){
					document.getElementById("laststep").disabled=false;
					window.location=D.U('update/three');
				},3000);
			}
			setLaststep();
		</script>
NEEDFORBUG;

		exit();
	}

	public function three(){
		global $hConn,$sSql4Tmp,$sDbprefix,$nMysqlVersion;

		$this->check_update();

		$this->db_connect();
		
		// 加载升级界面
		$this->assign('sUpdateTitle',Dyhb::L('数据库数据添加与更新','Controller/Update'));
		$this->display('update_message');

		// 开始清理数据库数据
		Install_Extend::showJavascriptMessage('<h3>'.Dyhb::L('多余数据库结构删除','Controller/Update').'</h3>');
		Install_Extend::runQuery(APP_PATH.'/Static/Sql/Update/needforbug.delete.sql');

		// 写入锁定文件
		if(!file_put_contents($this->_sUpdatefile,'ok')){
			$this->E(Dyhb::L('写入升级锁定文件失败，请检查%s目录是否可写入','Controller/Update',null,NEEDFORBUG_PATH.'/data'));
		}
		Install_Extend::showJavascriptMessage(Dyhb::L('写入升级程序锁定文件','Controller/Update').'... '.Dyhb::L('成功','Controller/Common'));
		Install_Extend::showJavascriptMessage(' ');

		// 执行清理
		if(is_dir(NEEDFORBUG_PATH.'/data/~runtime')){
			Install_Extend::showJavascriptMessage('<h3>'.Dyhb::L('清理系统缓存目录','Controller/Update').'</h3>');
			Install_Extend::removeDir(NEEDFORBUG_PATH.'/data/~runtime');
		}

		// 初始化系统和跳转
		$sInitsystemUrl=trim(G::getGpc('baseurl')).'/index.php?app=home&c=misc&a=init_system';
		
		echo<<<NEEDFORBUG
		<script type="text/javascript">
			function setLaststep(){
				setTimeout(function(){
					document.getElementById("laststep").disabled=false;
					window.location=D.U('update/success');
				},1000);
			}
		</script>
		<script type="text/javascript">setTimeout(function(){window.location=window.location=D.U('update/success');},30000);
		</script>
		<iframe src="{$sInitsystemUrl}" style="display:none;" onload="setLaststep()"></iframe>
NEEDFORBUG;

		exit();
	}

	public function success(){
		$this->display('update+success');
	}

}
