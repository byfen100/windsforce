<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   短消息模型($)*/

!defined('DYHB_PATH') && exit;

class PmModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'pm',
			'props'=>array(
				'pm_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'pm_id',
			'check'=>array(
				'pm_message'=>array(
					array('require',Dyhb::L('短消息内容不能为空','__COMMON_LANG__@Model/Pm')),
					array('max_length',1000,Dyhb::L('短消息内容最大长度为1000个字符','__COMMON_LANG__@Model/Pm')),
				),
				'pm_subject'=>array(
					array('empty'),
					array('max_length',1000,Dyhb::L('短消息主题最大长度为225个字符','__COMMON_LANG__@Model/Pm')),
				),
			),
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}

	public function sendAPm($sMessageto,$nUserId,$sUserName,$sSubject='',$sApp=''){
		$oUser=UserModel::F()->getByuser_name($sMessageto);
		
		$oPm=new self();
		$oPm->pm_msgfrom=$sUserName;
		$oPm->pm_msgfromid=$nUserId;
		$oPm->pm_msgtoid=$oUser['user_id'];
		$oPm->pm_status=1;
		$oPm->pm_subject=$sSubject;
		$oPm->pm_fromapp=$sApp;
		$oPm->save(0);
		
		if($oPm->isError()){
			$this->setErrorMessage($oPm->setErrorMessage());
			return false;
		}else{
			return $oPm;
		}
	}

	public function readSystemmessage($nPmId){
		$oPmsystemread=PmsystemreadModel::F('user_id=? AND pm_id=?',$GLOBALS['___login___']['user_id'],$nPmId)->query();

		if(!empty($oPmsystemread['user_id'])){
			return true;
		}else{
			$oPmsystemread=new PmsystemreadModel();
			$oPmsystemread->user_id=$GLOBALS['___login___']['user_id'];
			$oPmsystemread->pm_id=$nPmId;
			$oPmsystemread->save(0);
				
			if($oPmsystemread->isError()){
				$this->setErrorMessage($oPmsystemread->getErrorMessage());
			}

			return $oPmsystemread;
		}
	}

	public function deleteSystemmessage($nPmId){
		$oPmsystemdelete=PmsystemdeleteModel::F('user_id=? AND pm_id=?',$GLOBALS['___login___']['user_id'],$nPmId)->query();

		if(!empty($oPmsystemdelete['user_id'])){
			return true;
		}else{
			$oPmsystemdelete=new PmsystemdeleteModel();
			$oPmsystemdelete->user_id=$GLOBALS['___login___']['user_id'];
			$oPmsystemdelete->pm_id=$nPmId;
			$oPmsystemdelete->save(0);
				
			if($oPmsystemdelete->isError()){
				$this->setErrorMessage($oPmsystemdelete->getErrorMessage());
			}

			return $oPmsystemdelete;
		}
	}

}
