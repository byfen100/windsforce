<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   用户申诉管理控制器($)*/

!defined('DYHB_PATH') && exit;

class AppealController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['appeal_email']=array('like',"%".G::getGpc('appeal_email')."%");

		$nType=intval(G::getGpc('type','G'));
		if($nType<4 && $nType>=0){
			$arrMap['appeal_progress']=$nType;
		}

		$this->assign('nType',$nType);
	}

	public function show(){
		$nAppealId=intval(G::getGpc('id','G'));

		if(!empty($nAppealId)){
			$oAppeal=AppealModel::F('appeal_id=?',$nAppealId)->getOne();
			$oUser=UserModel::F('user_id=?',$oAppeal->user_id)->getOne();
			
			if($oAppeal->appeal_progress==0){
				$oAppeal->appeal_progress=1;
				$oAppeal->save(0,'update');
				if($oAppeal->isError()){
					$this->E(Dyhb::L('用户信息更新错','Controller/Appeal'));
				}
			}

			$this->assign('oAppeal',$oAppeal);
			$this->assign('oUser',$oUser);

			$this->display();
		}else{
			$this->E(Dyhb::L('无法获取用户申诉信息','Controller/Appeal'));
		}
	}

	public function pass(){
		$nAppealId=intval(G::getGpc('id','G'));

		if(!empty($nAppealId)){
			$oAppeal=AppealModel::F('appeal_id=?',$nAppealId)->getOne();
			$oAppeal->appeal_progress=2;
			$oAppeal->save(0,'update');

			if(!$oAppeal->isError()){
				$sEmail=$oAppeal->appeal_email;
				$oUser=UserModel::F('user_id=?',$oAppeal->user_id)->getOne();
				$sRandom=G::randString(6);
				$sNewPassword=G::randString(8);
				$sPassword=md5(md5(trim($sNewPassword)).trim($sRandom));
				$oUser->user_password=$sNewPassword;
				$oUser->user_random=$sRandom;
				$sTemppassword=md5(G::randString(32));
				$oUser->user_temppassword=$sTemppassword;
				$oUser->save(0,'update');
				if($oUser->isError()){
					$this->E($oUser->getErrorMessage());
				}
				
				$sGetPasswordUrl=$GLOBALS['_option_']['site_url'].'/index.php?app=home&c=getpassword&a=reset&email='.urlencode($sEmail).'&appeal=1'.'&hash='.urlencode(G::authcode($sTemppassword,false,null,$GLOBALS['_option_']['appeal_expired']));
				$oMailModel=Dyhb::instance('MailModel');
				$oMailConnect=$oMailModel->getMailConnect();

				$sEmailSubject=Dyhb::L('会员申诉密码重置','Controller/Appeal');
				$sNlbr=$oMailConnect->getIsHtml()===true?'<br/>':"\r\n";
				$sEmailContent='';
				$sEmailContent.=Dyhb::L('尊敬的','Controller/Appeal').$oUser->user_name.Dyhb::L('用户','Controller/Appeal').':'.$sNlbr.$sNlbr;
				$sEmailContent.=Dyhb::L('您的申诉已通过','Controller/Appeal').$sNlbr.$sNlbr;
				$sEmailContent.=Dyhb::L('您的新密码是','Controller/Appeal').':'.$sNewPassword.$sNlbr.$sNlbr;
				$sEmailContent.=Dyhb::L('您也可以点击下面链接重置密码','Controller/Appeal').':'.$sNlbr;
				$sEmailContent.="<a href=\"{$sGetPasswordUrl}\">{$sGetPasswordUrl}</a>".$sNlbr.$sNlbr;
				$sEmailContent.="-----------------------------------------------------".$sNlbr;
				$sEmailContent.=Dyhb::L('这是系统用于重置密码的邮件，请勿回复','Controller/Appeal').$sNlbr;
				$sEmailContent.=Dyhb::L('链接过期时间','Controller/Appeal').$GLOBALS['_option_']['getpassword_expired'].
					Dyhb::L('秒','Controller/Appeal').$sNlbr;

				$oMailConnect->setEmailTo($sEmail);
				$oMailConnect->setEmailSubject($sEmailSubject);
				$oMailConnect->setEmailMessage($sEmailContent);
				$oMailConnect->send();
				if($oMailConnect->isError()){
					$this->E($oMailConnect->getErrorMessage());
				}

				$this->S(Dyhb::L('审核通过','Controller/Appeal'));
			}else{
				$this->E(Dyhb::L('审核失败','Controller/Appeal'));
			}
		}else{
			$this->E(Dyhb::L('无法获取用户申诉信息','Controller/Appeal'));
		}
	}

	public function reject(){
		$nAppealId=intval(G::getGpc('id','G'));
		$sAppealReason=trim(G::getGpc('appeal_reason','P'));

		if(!empty($nAppealId)){
			$oAppeal=AppealModel::F('appeal_id=?',$nAppealId)->getOne();
			$oAppeal->appeal_progress=3;
			$oAppeal->appeal_reason=$sAppealReason;
			$oAppeal->save(0,'update');
			if(!$oAppeal->isError()){
				$sEmail=$oAppeal->appeal_email;
				
				$sAppealUrl=$GLOBALS['_option_']['site_url'].'/index.php?app=home&c=userappeal&a=index';

				$oMailModel=Dyhb::instance('MailModel');
				$oMailConnect=$oMailModel->getMailConnect();

				$sEmailSubject=Dyhb::L('会员申诉驳回','Controller/Appeal');
				$sNlbr=$oMailConnect->getIsHtml()===true?'<br/>':"\r\n";
				$sEmailContent='';
				$sEmailContent.=Dyhb::L('驳回理由','Controller/Appeal').':'.$sAppealReason.$sNlbr.$sNlbr;
				$sEmailContent.=Dyhb::L('申诉页面链接','Controller/Appeal').':'.$sNlbr;
				$sEmailContent.="<a href=\"{$sAppealUrl}\">{$sAppealUrl}</a>".$sNlbr.$sNlbr;
				$sEmailContent.="-----------------------------------------------------".$sNlbr;
				$sEmailContent.=Dyhb::L('这是系统用于申诉驳回的邮件，请勿回复','Controller/Appeal').$sNlbr;

				$oMailConnect->setEmailTo($sEmail);
				$oMailConnect->setEmailSubject($sEmailSubject);
				$oMailConnect->setEmailMessage($sEmailContent);
				$oMailConnect->send();
				if($oMailConnect->isError()){
					$this->E($oMailConnect->getErrorMessage());
				}

				$this->assign('__JumpUrl__',Dyhb::U('Appeal/index'));

				$this->S(Dyhb::L('审核驳回','Controller/Appeal'));
			}else{
				$this->E(Dyhb::L('驳回失败','Controller/Appeal'));
			}
		}else{
			$this->E(Dyhb::L('无法获取用户申诉信息','Controller/Appeal'));
		}
	}

}
