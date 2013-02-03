<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   用户申诉第四步($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Step4Controller extends GlobalchildController{

	public function index(){
		if(UserModel::M()->isLogin()){
			$this->U('home://ucenter/index');
		}

		$nEmaillink=intval(G::getGpc('emaillink'));

		if($nEmaillink!=1){
			$this->_oParentcontroller->check_seccode(true);
		}
		
		$sRealname=trim(G::getGpc('real_name'));
		$sAddress=trim(G::getGpc('address'));
		$sIdnumber=trim(G::getGpc('id_number'));
		$sAppealemail=trim(G::getGpc('appeal_email'));
		$sUserid=trim(G::getGpc('user_id'));
		$sHashcode=trim(G::getGpc('hashcode','P'));
		$sOldHashcode=trim(G::getGpc('old_hashcode','P'));

		$sUserid=G::authcode($sUserid);
		if(empty($sUserid)){
			$this->E(Dyhb::L('页面已过期','Controller/Userappeal'));
		}

		$oUser=UserModel::F('user_id=?',$sUserid)->getOne();
		if(empty($oUser->user_id)){
			$this->E(Dyhb::L('Email账号不存在','Controller/Userappeal'));
		}

		if($oUser->user_status==0){
			$this->E(Dyhb::L('该账户已经被禁止','Controller/Userappeal'));
		}

		if($nEmaillink!=1){
			if(empty($sHashcode)){
				$this->E(Dyhb::L('申诉验证码不能为空','Controller/Userappeal'));
			}

			$sOldHashcode=G::authcode($sOldHashcode);
			if(empty($sOldHashcode)){
				$this->E(Dyhb::L('申诉验证码已过期','Controller/Userappeal'));
			}

			if($sOldHashcode!=$sHashcode){
				$this->E(Dyhb::L('申诉验证码错误','Controller/Userappeal'));
			}
		}

		$sReceiptnumber=G::randString(32);

		// 将申诉信息保存到数据库
		$oAppeal=new AppealModel();
		$oAppeal->user_id=intval($sUserid);
		$oAppeal->appeal_realname=$sRealname;
		$oAppeal->appeal_address=$sAddress;
		$oAppeal->appeal_idnumber=$sIdnumber;
		$oAppeal->appeal_email=$sAppealemail;
		$oAppeal->appeal_receiptnumber=$sReceiptnumber;
		$oAppeal->save(0);

		if($oAppeal->isError()){
			$this->E($oAppeal->getErrorMessage());
		}
	
		$sUserid=G::authcode($oAppeal['user_id'],false,null,$GLOBALS['_option_']['appeal_expired']);
		
		$this->assign('sUserid',$sUserid);
		$this->assign('oAppeal',$oAppeal);

		$this->display('userappeal+step4');
	}

	public function step4_title_(){
		return Dyhb::L('获取申诉回执编号','Controller/Userappeal');
	}

	public function step4_keywords_(){
		return $this->step4_title_();
	}

	public function step4_description_(){
		return $this->step4_title_();
	}

}
