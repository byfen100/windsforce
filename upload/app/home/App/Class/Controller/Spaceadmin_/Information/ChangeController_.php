<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   个人信息修改保存($)*/

!defined('DYHB_PATH') && exit;

class ChangeController extends GlobalchildController{

	public function index(){
		if($GLOBALS['_option_']['seccode_changeinformation_status']==1){
			$this->_oParentcontroller->check_seccode(true);
		}

		$nUserId=G::getGpc('user_id','P');
		$oUser=UserModel::F('user_id=?',$nUserId)->query();
		$oUser->safeInput();
		$arrUserprofilesettings=UserprofilesettingModel::F()->getAll();
		if(is_array($arrUserprofilesettings)){
			foreach($arrUserprofilesettings as $oUserprofilesetting){
				if(isset($_POST[$oUserprofilesetting['userprofilesetting_id']])){
					if(in_array($oUserprofilesetting['userprofilesetting_id'],array('userprofile_bio','userprofile_interest','user_remark','user_sign'))){
						$oUser->userprofile->{$oUserprofilesetting['userprofilesetting_id']}=G::cleanJs($_POST[$oUserprofilesetting['userprofilesetting_id']]);
					}else{
						$oUser->userprofile->{$oUserprofilesetting['userprofilesetting_id']}=$_POST[$oUserprofilesetting['userprofilesetting_id']];
					}
				}
			}
		}

		$oUser->save(1,'update');

		if($oUser->isError()){
			$this->E($oUser->getErrorMessage());
		}else{
			$this->S(Dyhb::L('修改用户资料成功','Controller/Spaceadmin'));
		}
	}

}
