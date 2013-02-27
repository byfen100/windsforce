<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户申诉信息保存到邮件($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TomailController extends GlobalchildController{

	public function index(){
		if(UserModel::M()->isLogin()){
			$this->U('home://ucenter/index');
		}

		$nAppealId=intval(G::getGpc('id','G'));
		$sUserid=trim(G::getGpc('user_id','G'));

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

		if(empty($nAppealId)){
			$this->E(Dyhb::L('无法获取申诉ID','Controller/Userappeal'));
		}

		$oAppeal=AppealModel::F('appeal_id=?',$nAppealId)->getOne();

		if(empty($oAppeal->appeal_id)){
			$this->E(Dyhb::L('无效的申诉ID','Controller/Userappeal'));
		}
		
		$oMailModel=Dyhb::instance('MailModel');
		$oMailConnect=$oMailModel->getMailConnect();

		$sAppealscheduleUrl=$GLOBALS['_option_']['site_url'].'/index.php?app=home&c=userappeal&a=schedule';
		$sNlbr=$oMailConnect->getIsHtml()===true?'<br/>':"\r\n";

		$sEmailSubject=$GLOBALS['_option_']['site_name'].Dyhb::L('用户申诉回执单','Controller/Userappeal');
		$sEmailContent='<b>'.Dyhb::L('尊敬的用户','Controller/Userappeal').':</b>'.$sNlbr;
		$sEmailContent.='-----------------------------------------------------'.$sNlbr;
		$sEmailContent.=Dyhb::L('申诉人','Controller/Userappeal').':'.$oAppeal->appeal_realname.$sNlbr.$sNlbr;
		$sEmailContent.=Dyhb::L('申诉回执编号','Controller/Userappeal').':'.$oAppeal->appeal_receiptnumber.$sNlbr.$sNlbr;
		$sEmailContent.='--'.Dyhb::L('请牢记你的申诉编号，以便于随时查询申诉进度','Controller/Userappeal').$sNlbr;
		$sEmailContent.="<a href=\"{$sAppealscheduleUrl}\">{$sAppealscheduleUrl}</a>".$sNlbr.$sNlbr;
		$sEmailContent.=Dyhb::L('接受申诉结果的Email','Controller/Userappeal').':'.$oAppeal->appeal_email.$sNlbr.$sNlbr;
		$sEmailContent.='-----------------------------------------------------'.$sNlbr;
		$sEmailContent.=date('Y-m-d H:i',CURRENT_TIMESTAMP);

		$oMailConnect->setEmailTo($oAppeal->appeal_email);
		$oMailConnect->setEmailSubject($sEmailSubject);
		$oMailConnect->setEmailMessage($sEmailContent);
		$oMailConnect->send();
		if($oMailConnect->isError()){
			$this->E($oMailConnect->getErrorMessage());
		}

		$this->assign('__WaitSecond__',5);
		$this->assign('__JumpUrl__','javascript:history.back(-1);');

		$this->S(Dyhb::L('申诉回执编号已发送到您的邮箱','Controller/Userappeal').' '.$oAppeal->appeal_email);
	}

}
