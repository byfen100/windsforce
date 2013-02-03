<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   公用控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class InitController extends Controller{

	public $_arrOptions=array();

	public function init__(){
		parent::init__();

		//if(preg_match('/(mozilla|m3gate|winwap|openwave)/i',$_SERVER['HTTP_USER_AGENT'])){
			//G::urlGoTo(__ROOT__.'/index.php');
		//}

		//header("Content-type: text/vnd.wap.wml; charset=utf-8");

		// 配置&登陆信息
		Core_Extend::loadCache('option');
		Core_Extend::loginInformation();

		// 404
		Core_Extend::page404($this);

		$this->init();
	}

	protected function init(){
		/*if($GLOBALS['_option_']['close_site']==1){
			$this->assign('__JumpUrl__',Dyhb::U('wap://public/index'));
			$this->wap_mes($GLOBALS['_option_']['close_site_why']);
		}

		if($GLOBALS['_option_']['close_wap']==1){
			$this->assign('__JumpUrl__',Dyhb::U('wap://public/index'));
			$this->wap_mes('网站wap已关闭');
		}*/
	}

	public function page404(){
		header("HTTP/1.0 404 Not Found");
		$this->display('404');

		exit();
	}

	public function wap_mes($sMsg,$sLink=''){
		if(empty($sLink)){
			$sLink=Dyhb::U('wap://public/index');
		}

		$this->assign('__JumpUrl__',$sLink);
		$this->assign('__Message__',$sMsg);
		$this->display('message');

		exit();
	}
}
