<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   前台个人信息管理($)*/

!defined('DYHB_PATH') && exit;

class InformationController extends GlobalchildController{

	public function index(){
		require_once(Core_Extend::includeFile('function/Profile_Extend'));
		
		$sDo=G::getGpc('do','G');
		if(!in_array($sDo,array('','base','contact','edu','work','info'))){
			$sDo='';
		}

		Core_Extend::loadCache('userprofilesetting');
		$this->assign('arrUserprofilesettingDatas',$GLOBALS['_cache_']['userprofilesetting']);
		
		$arrUserData=$GLOBALS['___login___'];
		$oUserInfo=UserModel::F()->getByuser_id($arrUserData['user_id']);
		$this->assign('oUserInfo',$oUserInfo);

		$arrUserprofile=$oUserInfo->userprofile->toArray();
		$nNowYear=date('Y',CURRENT_TIMESTAMP);
		if(in_array($arrUserprofile['userprofile_birthmonth'],array(1,3,5,7,8,10,12))){
			$nDays=31;
		}elseif(in_array($arrUserprofile['userprofile_birthmonth'],array(4,6,9,11))){
			$nDays=30;
		}elseif($arrUserprofile['userprofile_birthyear'] &&
			(($arrUserprofile['userprofile_birthyear']%400==0) || ($arrUserprofile['userprofile_birthyear']%4==0 && $arrUserprofile['userprofile_birthyear']%400!=0))
		){
			$nDays=29;
		}else{
			$nDays=28;
		}

		$this->assign('nNowYear',$nNowYear);
		$this->assign('nNowDays',$nDays);
		$this->assign('sDirthDistrict',Profile_Extend::getDistrict($arrUserprofile,'birth'));
		$this->assign('sResideDistrict',Profile_Extend::getDistrict($arrUserprofile,'reside'));
		$this->assign('sDo',$sDo);

		// 视图
		$arrProfileSetting=Profile_Extend::getProfileSetting();

		$this->assign('arrBases',$arrProfileSetting[0]);
		$this->assign('arrContacts',$arrProfileSetting[1]);
		$this->assign('arrEdus',$arrProfileSetting[2]);
		$this->assign('arrWorks',$arrProfileSetting[3]);
		$this->assign('arrInfos',$arrProfileSetting[4]);

		$arrInfoMenus=Profile_Extend::getInfoMenu();

		$this->assign('arrInfoMenus',$arrInfoMenus);
		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['seccode_changeinformation_status']);

		$this->display('spaceadmin+index');
	}

	public function index_title_(){
		return Dyhb::L('修改资料','Controller/Spaceadmin');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

	public function change(){
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
