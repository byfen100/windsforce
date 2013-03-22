<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   验证信息确认($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CheckController extends Controller{

	public function index(){
		$sEmail=trim(G::getGpc('email','G'));
		$sHash=trim(G::getGpc('hash','G'));

		if(empty($sHash)){
			$this->U('home://spaceadmin/verifyemail');
		}

		$sHash=G::authcode($sHash);

		if(empty($sHash)){
			$this->assign('__JumpUrl__',Dyhb::U('home://spaceadmin/verifyemail'));
			$this->E(Dyhb::L('Email验证链接已过期','Controller/Spaceadmin'));
		}
		
		$oUser=UserModel::F('user_email=? AND user_verifycode=?',$sEmail,$sHash)->getOne();
		
		if(empty($oUser->user_id)){
			$this->assign('__JumpUrl__',Dyhb::U('home://spaceadmin/verifyemail'));
			$this->E(Dyhb::L('Email验证链接已过期','Controller/Spaceadmin'));
		}

		// 确认验证状态
		$oUser->user_verifycode='';
		$oUser->user_isverify='1';
		$oUser->setAutofill(false);
		$oUser->save(0,'update');

		if($oUser->isError()){
			$this->E($oUser->getErrorMessage());
		}

		// 更新积分
		Core_Extend::updateCreditByAction('verifyemail',$GLOBALS['___login___']['user_id']);

		$this->assign('__JumpUrl__',Dyhb::U('home://spaceadmin/verifyemail'));
		$this->S(Dyhb::L('恭喜Email验证通过','Controller/Spaceadmin'));
	}

}
