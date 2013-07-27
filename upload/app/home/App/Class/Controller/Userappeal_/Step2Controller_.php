<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户申诉第二步($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Step2Controller extends GlobalchildController{

	public function index(){
		if(UserModel::M()->isLogin()){
			$this->U('home://ucenter/index');
		}

		$this->_oParentcontroller->check_seccode(true);
	
		$sUsername=trim(G::getGpc('user_name','P'));
		if(Core_Extend::isPostInt($sUsername)){
			$oUser=UserModel::F('user_id=?',$sUsername)->getOne();
		}else{
			$oUser=UserModel::F('user_name=?',$sUsername)->getOne();
		}
		
		if(empty($oUser->user_id)){
			$this->E(Dyhb::L('用户名或者用户ID不存在','Controller'));
		}

		if($oUser->user_status==0){
			$this->E(Dyhb::L('该账户已经被禁止','Controller'));
		}

		$sUserid=G::authcode($oUser['user_id'],false,null,$GLOBALS['_option_']['appeal_expired']);
		$this->assign('sUserid',$sUserid);

		$this->display('userappeal+step2');
	}

	public function step2_title_(){
		return Dyhb::L('填写联系方式','Controller');
	}

	public function step2_keywords_(){
		return $this->step2_title_();
	}

	public function step2_description_(){
		return $this->step2_title_();
	}

}
