<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   申诉结果($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ScheduleresultController extends GlobalchildController{

	public function index(){
		if(UserModel::M()->isLogin()){
			$this->U('home://ucenter/index');
		}

		$this->_oParentcontroller->check_seccode(true);

		$sAppealReceiptnumber=trim(G::getGpc('appeal_receiptnumber','P'));
		$sAppealEmail=trim(G::getGpc('appeal_email','P'));
		if(empty($sAppealReceiptnumber)){
			$this->E(Dyhb::L('申诉回执编号不能为空','Controller/Userappeal'));
		}

		if(empty($sAppealEmail)){
			$this->E(Dyhb::L('申诉邮箱不能为空','Controller/Userappeal'));
		}

		Check::RUN();
		if(!Check::C($sAppealEmail,'email')){
			$this->E(Dyhb::L('申诉邮箱错误','Controller/Userappeal'));
		}

		$oAppeal=AppealModel::F('appeal_email=? AND appeal_receiptnumber=?',$sAppealEmail,$sAppealReceiptnumber)->getOne();
		if(empty($oAppeal->appeal_id)){
			$this->E(Dyhb::L('申诉回执编号或者申诉邮箱错误,又或者该申诉回执已被删除','Controller/Userappeal'));
		}

		if($oAppeal->appeal_status==0){
			$this->E(Dyhb::L('该申诉回执已经被关闭','Controller/Userappeal'));
		}

		$this->assign('oAppeal',$oAppeal);
		
		$this->display('userappeal+scheduleresult');
	}

	public function schedule_result_title_(){
		return Dyhb::L('申诉结果','Controller/Userappeal');
	}

	public function schedule_result_keywords_(){
		return $this->schedule_result_title_();
	}

	public function schedule_result_description_(){
		return $this->schedule_result_title_();
	}

}
