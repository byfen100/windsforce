<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   邮件验证($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		// 判断是否已经发送了验证邮件
		$oUser=UserModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getOne();
		if(!empty($oUser['user_id'])){
			$this->assign('bSendemail',$oUser['user_verifycode']?true:false);
		}

		$this->assign('arrUserlogin',$GLOBALS['___login___']);

		$this->display('spaceadmin+verifyemail');
	}

	public function verifyemail_title_(){
		return Dyhb::L('Email验证','Controller/Spaceadmin');
	}

	public function verifyemail_keywords_(){
		return $this->verifyemail_title_();
	}

	public function verifyemail_description_(){
		return $this->verifyemail_title_();
	}

}
