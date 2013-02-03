<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   用户申诉信息保存到电脑($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TocomputerController extends GlobalchildController{

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

		$sName='APPEAL_'.date('Y_m_d_H_i_s',CURRENT_TIMESTAMP).'.txt';

		header('Content-Type: text/plain');
		header('Content-Disposition: attachment;filename="'.$sName.'"');
		if(preg_match("/MSIE([0-9].[0-9]{1,2})/",$_SERVER['HTTP_USER_AGENT'])){
			header('Cache-Control: must-revalidate,post-check=0,pre-check=0');
			header('Pragma: public');
		}else{
			header('Pragma: no-cache');
		}
		
		$sAppealscheduleUrl=$GLOBALS['_option_']['site_url'].'/index.php?app=home&c=userappeal&a=schedule';
		$sNlbr="\r\n";

		$sContent='['.$GLOBALS['_option_']['site_name'].']'.Dyhb::L('用户申诉回执单','Controller/Userappeal').$sNlbr;
		$sContent.='-----------------------------------------------------'.$sNlbr;
		$sContent.=Dyhb::L('申诉人','Controller/Userappeal').':'.$oAppeal->appeal_realname.$sNlbr.$sNlbr;
		$sContent.=Dyhb::L('申诉回执编号','Controller/Userappeal').':'.$oAppeal->appeal_receiptnumber.$sNlbr.$sNlbr;
		$sContent.='--'.Dyhb::L('请牢记你的申诉编号，以便于随时查询申诉进度','Controller/Userappeal').$sNlbr;
		$sContent.=$sAppealscheduleUrl.$sNlbr.$sNlbr;
		$sContent.=Dyhb::L('接受申诉结果的Email','Controller/Userappeal').':'.$oAppeal->appeal_email.$sNlbr.$sNlbr;
		$sContent.='-----------------------------------------------------'.$sNlbr;
		$sContent.=date('Y-m-d H:i',CURRENT_TIMESTAMP);

		echo $sContent;
	}

}
