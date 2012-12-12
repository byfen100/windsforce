<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   重置密码页面($)*/

!defined('DYHB_PATH') && exit;

class ResetController extends Controller{

	public function index(){
		if(UserModel::M()->isLogin()){
			$this->U('home://spaceadmin/password');
		}

		$sEmail=trim(G::getGpc('email','G'));
		$sHash=trim(G::getGpc('hash','G'));
		$nAppeal=intval(G::getGpc('appeal','G'));

		if(empty($sHash)){
			$this->U('home://getpassword/index');
		}

		$sHash=G::authcode($sHash);

		if(empty($sHash)){
			$this->assign('__JumpUrl__',Dyhb::U('home://getpassword/index'));
			$this->E(Dyhb::L('找回密码链接已过期','Controller/Getpassword'));
		}
		
		if($nAppeal==1){
			$oUser=UserModel::F('user_temppassword=?',$sHash)->getOne();
		}else{
			$oUser=UserModel::F('user_email=? AND user_temppassword=?',$sEmail,$sHash)->getOne();
		}
		
		if(empty($oUser->user_id)){
			$this->assign('__JumpUrl__',Dyhb::U('home://getpassword/index'));
			$this->E(Dyhb::L('找回密码链接已过期','Controller/Getpassword'));
		}

		$this->assign('sEmail',$sEmail);
		$this->assign('nAppeal',$nAppeal);
		$this->assign('user_id',$oUser->user_id);

		$this->display('getpassword+reset');
	}

	public function reset_title_(){
		return Dyhb::L('密码重置','Controller/Getpassword');
	}

	public function reset_keywords_(){
		return $this->reset_title_();
	}

	public function reset_description_(){
		return $this->reset_title_();
	}

}
