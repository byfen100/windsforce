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
 //  | websetup Init控制器
 //  +---------------------------------------------------------------------

*/

!defined('DYHB_PATH') && exit;

class InitController extends Controller{

	public function __construct(){
		parent::__construct();

		if(is_file(WEBSETUP_PATH.'/~Data/websetup/WebsetupLock.php')){
			$this->assign('bIsLock',TRUE);
			$sPath=WEBSETUP_PATH.'/~Data/websetup/WebsetupLock.php';
			$sPath=str_replace(G::tidyPath(dirname(DYHB_PATH)),'{DYHB_PATH}..',G::tidyPath($sPath));
			$this->E(Dyhb::L("本工具已经被锁定了，请删除文件 < %s > 解锁才能够使用！",'app',null,$sPath));
		}
		$this->assign('bIsLock',false);

		$sDyhbWebsetupApp=Dyhb::cookie( 'dyhb_websetup_app');
		$this->assign('sDyhbWebsetupApp',$sDyhbWebsetupApp);
		$this->assign('arrListLangs',G::listDir(APP_PATH.'/App/Lang'));
	}

}
