<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   发送短消息处理逻辑($)*/

!defined('DYHB_PATH') && exit;

class SendpmController extends GlobalchildController{

	public function index(){
		$this->_oParentcontroller->check_pm();
		
		$arrOptionData=$GLOBALS['_option_'];
		if($arrOptionData['pmsend_seccode']==1){
			$this->_oParentcontroller->check_seccode(true);
		}
		
		$sMessageto=trim(G::getGpc('messageto'));
		$sPmMessage=trim(G::getGpc('pm_message'));
		$sPmSubject=trim(G::getGpc('pm_subject'));
		
		if(empty($sMessageto)){
			$this->E(Dyhb::L('收件用户不能为空','Controller/Pm'));
		}
		
		$arrUsers=Core_Extend::segmentUsername($sMessageto);
		
		$oLastPmModel=null;
		foreach($arrUsers as $sUser){
			if(empty($sUser)){
				continue;
			}
				
			if($sUser==$GLOBALS['___login___']['user_name']){
				$this->E(Dyhb::L('收件用户中不能有自己','Controller/Pm'));
			}
			
			if(!preg_match("/[^\d-.,]/",$sUser)){
				$oTryUser=UserModel::F('user_id=? AND user_status=1',$sUser)->getOne();
			}else{
				$oTryUser=UserModel::F('user_name=? AND user_status=1',$sUser)->getOne();
			}

			if(empty($oTryUser['user_id'])){
				$this->E(Dyhb::L('用户 %s 不存在或者尚未审核通过','Controller/Pm',null,$sUser));
			}

			$arrUserInfo=$GLOBALS['___login___'];
		
			$oPmModel=Dyhb::instance('PmModel');
			$oLastPmModel=$oPmModel->sendAPm($sUser,$arrUserInfo['user_id'],$arrUserInfo['user_name'],$sPmSubject,'home');
		
			if($oPmModel->isError()){
				$this->E($oPmModel->getErrorMessage());
			}
		}

		// 更新积分
		Core_Extend::updateCreditByAction('sendpm',$GLOBALS['___login___']['user_id']);
		
		// 成功消息
		if(G::getGpc('type')=='back'){
			$arrData=$oLastPmModel->toArray();
			$arrData['jumpurl']=($GLOBALS['_commonConfig_']['URL_MODEL'] && $GLOBALS['_commonConfig_']['URL_MODEL']!=3?'?':'&').
				'extra=new'.$arrData['pm_id'].'#pm-'.$arrData['pm_id'];

			$this->A($arrData,Dyhb::L('发送短消息成功','Controller/Pm'),1);
		}else{
			$arrData=$oLastPmModel->toArray();
			$arrData['jumpurl']=Dyhb::U('home://pm/show?id='.$arrData['pm_id'].'&muid='.$arrData['pm_msgfromid']);
			
			$this->A($arrData,Dyhb::L('发送短消息成功','Controller/Pm'),1);
		}
	}

}
