<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   对话框发送短消息($)*/

!defined('DYHB_PATH') && exit;

class DialogaddController extends GlobalchildController{

	public function index(){
		$this->_oParentcontroller->check_pm();
		
		$nUserid=intval(G::getGpc('uid','G'));

		$sUserName='';
		if(!empty($nUserid)){
			$oUser=UserModel::F('user_id=?',$nUserid)->getOne();
			if(!empty($oUser['user_id'])){
				$sUserName=$oUser['user_name'];
			}
		}

		$this->assign('sUserName',$sUserName);
		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['pmsend_seccode']);
		$this->assign('sContent','');

		$this->display('pm+dialogadd');
	}

}
