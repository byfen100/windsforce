<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   通过邮件发送密码重置链接($)*/

!defined('DYHB_PATH') && exit;

class EmailController extends GlobalchildController{

	public function index(){
		if(UserModel::M()->isLogin()){
			$this->U('home://spaceadmin/password');
		}

		$this->_oParentcontroller->check_seccode(true);

		$sEmail=trim(G::getGpc('user_email','P'));
		if(empty($sEmail)){
			$this->E(Dyhb::L('Email地址不能为空','Controller/Getpassword'));
		}

		Check::RUN();
		if(!Check::C($sEmail,'email')){
			$this->E(Dyhb::L('Email格式不正确','Controller/Getpassword'));
		}

		$oUser=UserModel::F('user_email=?',$sEmail)->getOne();
		if(empty($oUser->user_id)){
			$this->E(Dyhb::L('Email账号不存在','Controller/Getpassword'));
		}
		if($oUser->user_status==0){
			$this->E(Dyhb::L('该账户已经被禁止','Controller/Getpassword'));
		}

		$sTemppassword=md5(G::randString(32));
		$oUser->user_temppassword=$sTemppassword;
		$oUser->save(0,'update');
		if($oUser->isError()){
			$this->E($oUser->getErrorMessage());
		}
		
		$sGetPasswordUrl=$GLOBALS['_option_']['site_url'].'/index.php?app=home&c=getpassword&a=reset&email='.urlencode($sEmail).'&hash='.urlencode(G::authcode($sTemppassword,false,null,$GLOBALS['_option_']['getpassword_expired']));

		$oMailModel=Dyhb::instance('MailModel');
		$oMailConnect=$oMailModel->getMailConnect();

		$sEmailSubject=$GLOBALS['_option_']['site_name'].Dyhb::L('会员密码找回','Controller/Getpassword');
		$sNlbr=$oMailConnect->getIsHtml()===true?'<br/>':"\r\n";
		$sEmailContent=Dyhb::L('你的登录信息','Controller/Getpassword').':'.$sNlbr;
		$sEmailContent.='Email:'.$sEmail.$sNlbr;
		$sEmailContent.=Dyhb::L('重置密码链接','Controller/Getpassword').':'.$sNlbr;
		$sEmailContent.="<a href=\"{$sGetPasswordUrl}\">{$sGetPasswordUrl}</a>".$sNlbr.$sNlbr;
		$sEmailContent.="-----------------------------------------------------".$sNlbr;
		$sEmailContent.=Dyhb::L('这是系统用于找回密码的邮件，请勿回复','Controller/Getpassword').$sNlbr;
		$sEmailContent.=Dyhb::L('链接过期时间','Controller/Getpassword').':'.$GLOBALS['_option_']['getpassword_expired'].Dyhb::L('秒','__COMMON_LANG__@Common').$sNlbr;

		$oMailConnect->setEmailTo($sEmail);
		$oMailConnect->setEmailSubject($sEmailSubject);
		$oMailConnect->setEmailMessage($sEmailContent);
		$oMailConnect->send();
		if($oMailConnect->isError()){
			$this->E($oMailConnect->getErrorMessage());
		}

		$this->S(Dyhb::L('邮件已发送到你指定的邮箱','Controller/Getpassword'));
	}

}
