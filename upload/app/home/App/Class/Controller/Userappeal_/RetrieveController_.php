<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   用户申诉重发($)*/

!defined('DYHB_PATH') && exit;

class RetrieveController extends GlobalchildController{

	public function index(){
		$nAppealId=intval(G::getGpc('id','G'));

		if(!empty($nAppealId)){
			$oAppeal=AppealModel::F('appeal_id=?',$nAppealId)->getOne();

			$sEmail=$oAppeal->appeal_email;
			$oUser=UserModel::F('user_id=?',$oAppeal->user_id)->getOne();
			$sTemppassword=md5(G::randString(32));
			$oUser->user_temppassword=$sTemppassword;
			$oUser->save(0,'update');
			
			if($oUser->isError()){
				$this->E($oUser->getErrorMessage());
			}
			
			$sGetPasswordUrl=$GLOBALS['_option_']['site_url'].'/index.php?app=home&c=getpassword&a=reset&email='.urlencode($sEmail).'&appeal=1'.'&hash='.urlencode(G::authcode($sTemppassword,false,null,$GLOBALS['_option_']['appeal_expired']));

			$oMailModel=Dyhb::instance('MailModel');
			$oMailConnect=$oMailModel->getMailConnect();

			$sEmailSubject=$GLOBALS['_option_']['site_name'].Dyhb::L('会员申诉密码重置','Controller/Userappeal');
			$sNlbr=$oMailConnect->getIsHtml()===true?'<br/>':"\r\n";
			$sEmailContent='';
			$sEmailContent.=Dyhb::L('重置密码链接','Controller/Userappeal').':'.$sNlbr;
			$sEmailContent.="<a href=\"{$sGetPasswordUrl}\">{$sGetPasswordUrl}</a>".$sNlbr.$sNlbr;
			$sEmailContent.="-----------------------------------------------------".$sNlbr;
			$sEmailContent.=Dyhb::L('这是系统用于重置密码的邮件，请勿回复','Controller/Userappeal').$sNlbr;
			$sEmailContent.=Dyhb::L('链接过期时间','Controller/Userappeal').$GLOBALS['_option_']['appeal_expired'].
				Dyhb::L('秒','Controller/Userappeal').$sNlbr;

			$oMailConnect->setEmailTo($sEmail);
			$oMailConnect->setEmailSubject($sEmailSubject);
			$oMailConnect->setEmailMessage($sEmailContent);
			$oMailConnect->send();
			
			if($oMailConnect->isError()){
				$this->E($oMailConnect->getErrorMessage());
			}

			$this->S(Dyhb::L('发送成功,请注意查收','Controller/Userappeal'));
		}else{
			$this->E(Dyhb::L('读取数据失败','Controller/Userappeal'));
		}
	}

}
