<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Wap公用控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GlobalwapController extends Controller{

	public function init__(){
		// 配置&登陆信息
		Core_Extend::loadCache('option');
		Core_Extend::loginInformation();
		
		// 应用配置
		if(Dyhb::classExists(ucfirst(APP_NAME).'optionModel')){
			Core_Extend::loadCache(APP_NAME.'_option');
		}

		if($GLOBALS['_option_']['wap_computer_on']==1){
			if(preg_match('/(mozilla|m3gate|winwap|openwave)/i',$_SERVER['HTTP_USER_AGENT'])){
				G::urlGoTo(__ROOT__.'/index.php');
			}
		}

		if($GLOBALS['_option_']['wap_mobile_only']==1){
			header("Content-type: text/vnd.wap.wml; charset=utf-8");
		}

		UserModel::M()->checkRbac();
		if(UserModel::M()->isBehaviorError()){
			$this->wap_mes(UserModel::M()->getBehaviorErrorMessage(),'',0);
		}

		// 404
		Core_Extend::page404($this);

		$this->init();
	}

	protected function init(){
		if($GLOBALS['_option_']['close_site']==1){
			$this->assign('__JumpUrl__',Dyhb::U('wap://public/index'));
			$this->wap_mes($GLOBALS['_option_']['close_site_reason'],'',0);
		}

		if($GLOBALS['_option_']['wap_on']==0){
			$this->assign('__JumpUrl__',Dyhb::U('wap://public/index'));
			$this->wap_mes($GLOBALS['_option_']['wap_close_reason'],'',0);
		}
	}

	public function page404(){
		header("HTTP/1.0 404 Not Found");
		$this->wap_mes('404 未找到','',0);

		exit();
	}

	public function wap_mes($sMsg,$sLink='',$nStatus=1){
		if(empty($sLink)){
			$sLink=Dyhb::U('wap://public/index');
		}

		$this->assign('__JumpUrl__',$sLink);
		$this->assign('__Message__',$sMsg);
		$this->assign('nStatus',$nStatus);

		$this->display(WINDSFORCE_PATH.'/app/wap/Theme/Default/message.html');

		exit();
	}

	public function is_login(){
		if($GLOBALS['___login___']===false){
			UserModel::M()->clearThisCookie();
	
			if(!G::isAjax()){
				// 发送当前URL
				Dyhb::cookie('windsforce_referer',__SELF__);
			}
			
			$this->assign('__JumpUrl__',Dyhb::U('wap://public/login'));
			$this->wap_mes(Dyhb::L('你没有登录','__COMMON_LANG__@Common'),'',0);
		}
	}

}
