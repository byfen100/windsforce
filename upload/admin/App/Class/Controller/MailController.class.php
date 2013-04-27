<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   邮件管理控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class MailController extends InitController{

	public function bInsert_(){
		// 处理checkbox
		if(!isset($_POST['mail_level'])){
			$_POST['mail_level']=1;
		}

		if(!isset($_POST['mail_htmlon'])){
			$_POST['mail_htmlon']=1;
		}
	}

	public function bUpdate_(){
		$this->bInsert_();
	}

	public function send(){
		$nMailId=intval(G::getGpc('id','G'));

		if(empty($nMailId)){
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}

		$oMail=MailModel::F('mail_id=?',$nMailId)->query();
		if(empty($oMail['mail_id'])){
			$this->E(Dyhb::L('数据库中并不存在该项，或许它已经被删除','Controller/Common'));
		}

		// 发送邮件
		$oMailObject=Dyhb::instance('MailModel');
		$oMailConnect=$oMailObject->getMailConnect();

		$oMailObject->sendAEmail($oMailConnect,$oMail['mail_tomail'],$oMail['mail_subject'],($oMail['mail_htmlon']==0?strip_tags($oMail['mail_message']):$oMail['mail_message']),'admin',false);
		
		if($oMailObject->isError()){
			$this->E($oMailObject->getErrorMessage());
		}

		$this->S(Dyhb::L('邮件发送成功','Controller/Mail'));
	}

}
