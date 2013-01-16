<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   发送新的短消息($)*/

!defined('DYHB_PATH') && exit;

class PmnewController extends GlobalchildController{

	public function index(){
		try{
			Core_Extend::checkSpam();
		}catch(Exception $e){
			$this->E($e->getMessage());
		}
		
		$this->_oParentcontroller->check_pm();
		
		$nUserId=intval(G::getGpc('uid'));
		$nPmId=intval(G::getGpc('pmid'));
		
		if(!empty($nUserId)){
			$oUser=UserModel::F('user_id=?',$nUserId)->query();
			$sUserName=$oUser['user_name'];
		}else{
			$sUserName='';
		}
		
		if(!empty($nPmId)){
			$oPm=PmModel::F('pm_id=? AND pm_status=1',$nPmId)->query();
			
			if(!empty($nUserId) && !empty($oPm['pm_id'])){
				$this->assign('oPm',$oPm);

				// 回复短消息的同时也查看了短消息，所以这里将短消息标记为已经阅读
				if($oPm->pm_isread==0){
					$oPm->pm_isread=1;
					$oPm->save(0,'update');

					if($oPm->isError()){
						$this->E($oPm->getErrorMessage());
					}
				}
			}
			
			if(!empty($oPm['pm_id'])){
				$sContent=" \r\n[hr][pm]".$oPm['pm_id']."[/pm]\r\n";
				$sContent.=$oPm['pm_message']."\r\n";
			}else{
				$sContent='';
			}
		}else{
			$sContent='';
		}
		
		$this->assign('sUserName',$sUserName);
		$this->assign('sContent',$sContent);
		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['pmsend_seccode']);
		$this->assign('sType','pmnew');
		
		$this->display('pm+new');
	}

	public function pmnew_title_(){
		return Dyhb::L('新建短消息','Controller/Pm');
	}

	public function pmnew_keywords_(){
		return $this->pmnew_title_();
	}

	public function pmnew_description_(){
		return $this->pmnew_title_();
	}

}
