<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   个人信息处理相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Profile_Extend{
	
	static public function getDistrict($arrSpace=array(),$sDistrictName='birth',$bDisplayModify=true,$sDistrictPrefix='userprofile_',$bUserprofile=true){
		$arrValues=array(0,0,0,0);
		$arrElems=array($sDistrictName.'province',$sDistrictName.'city',$sDistrictName.'dist',$sDistrictName.'community');

		if($bDisplayModify===false || !empty($arrSpace[$sDistrictPrefix.$sDistrictName.'province'])){
			if($bUserprofile===true){
				$sHtml=self::profileShow($sDistrictPrefix.$sDistrictName.'city',$arrSpace);
			}else{
				$sHtml=$arrSpace[$sDistrictPrefix.$sDistrictName.'province']
					.(!empty($arrSpace[$sDistrictPrefix.$sDistrictName.'city'])?' '.$arrSpace[$sDistrictPrefix.$sDistrictName.'city']:'')
					.(!empty($arrSpace[$sDistrictPrefix.$sDistrictName.'dist'])?' '.$arrSpace[$sDistrictPrefix.$sDistrictName.'dist']:'')
					.(!empty($arrSpace[$sDistrictPrefix.$sDistrictName.'community'])?' '.$arrSpace[$sDistrictPrefix.$sDistrictName.'community']:'');
			}

			if($bDisplayModify===true){
				$sHtml.='&nbsp;(<a href="javascript:;" onclick="showDistrict(\''.$sDistrictName.'districtbox\',[\''.$sDistrictName.'province\',\''.$sDistrictName.'city\',\''.$sDistrictName.'dist\',\''.$sDistrictName.'community\'],4,\'\',\''.$sDistrictName.'\',\''.$sDistrictPrefix.'\'); return false;">'.Dyhb::L('修改','__COMMON_LANG__@Function/Profile_Extend').'</a>)';
				$sHtml.= '<p id="'.$sDistrictName.'districtbox"></p>';
			}
		}else{
			$sHtml='<p id="'.$sDistrictName.'districtbox">'.self::showDistrict($arrValues,$arrElems,$sDistrictName.'districtbox',1,$sDistrictName,$sDistrictPrefix).'</p>';
		}

		return $sHtml;
	}

	static public function profileShow($sFieldid,$arrSpace=array()){
		if(empty($GLOBALS['_cache_']['userprofilesetting'])){
			Core_Extend::loadCache('userprofilesetting');
		}

		if(isset($GLOBALS['_cache_']['userprofilesetting'][$sFieldid])){
			$arrField=$GLOBALS['_cache_']['userprofilesetting'][$sFieldid];
		}else{
			return false;
		}

		if(empty($arrField) || in_array($sFieldid,array('userprofile_birthprovince','userprofile_resideprovince'))){
			return false;
		}

		if($sFieldid=='userprofile_birthcity'){
			return $arrSpace['userprofile_birthprovince']
				.(!empty($arrSpace['userprofile_birthcity'])?' '.$arrSpace['userprofile_birthcity']:'')
				.(!empty($arrSpace['userprofile_birthdist'])?' '.$arrSpace['userprofile_birthdist']:'')
				.(!empty($arrSpace['userprofile_birthcommunity'])?' '.$arrSpace['userprofile_birthcommunity']:'');
		}elseif($sFieldid=='userprofile_residecity'){
			return $arrSpace['userprofile_resideprovince']
				.(!empty($arrSpace['userprofile_residecity'])?' '.$arrSpace['userprofile_residecity']:'')
				.(!empty($arrSpace['userprofile_residedist'])?' '.$arrSpace['userprofile_residedist']:'')
				.(!empty($arrSpace['userprofile_residecommunity'])?' '.$arrSpace['userprofile_residecommunity']:'');
		}
	}

	static public function showDistrict($arrValues,$arrElems=array(),$sContainer='districtbox',$nShowlevel=null,$sContainertype='birth',$sDistrictPrefix='userprofile_'){
		$sHtml='';

		if(!preg_match("/^[A-Za-z0-9_]+$/",$sContainer)){
			return $sHtml;
		}
	
		$nShowlevel=!empty($nShowlevel)?intval($nShowlevel):count($arrValues);
		$nShowlevel=$nShowlevel<=4?$nShowlevel:4;

		$arrUpids=array(0);
		for($nI=0;$nI<$nShowlevel;$nI++){
			if(!empty($arrValues[$nI])){
				$arrUpids[]=intval($arrValues[$nI]);
			}else{
				for($nJ=$nI;$nJ<$nShowlevel;$nJ++){
					$arrValues[$nJ]='';
				}
				break;
			}
		}

		$arr0ptions=array(1=>array(),2=>array(),3=>array(),4=>array());
		if($arrUpids && is_array($arrUpids)){
			foreach(self::getDistrictByUpid($arrUpids,'ASC') as $arrValue){
				if($arrValue['district_level']==1 &&
					($arrValue['district_id']!=$arrValues[0] &&
						(!($sContainertype=='birth' || $sContainertype!='birth'))
					)
				){
					continue;
				}
				$arrOptions[$arrValue['district_level']][]=array($arrValue['district_id'],$arrValue['district_name']);
			}
		}

		$arrNames=array('province','city','district','community');
		for($nI=0;$nI<4;$nI++){
			if(!empty($arrElems[$nI])){
				$arrElems[$nI]=htmlspecialchars(preg_replace("/[^\[A-Za-z0-9_\]]/",'',$arrElems[$nI]));
			}else{
				$arrElems[$nI]=($sContainertype=='birth'?'birth':'reside').$arrNames[$nI];
			}
		}

		for($nI=0;$nI<$nShowlevel;$nI++){
			$nLevel=$nI+1;

			if(!empty($arrOptions[$nLevel])){
				$sJscall="showDistrict('{$sContainer}',['{$arrElems[0]}','{$arrElems[1]}','{$arrElems[2]}','{$arrElems[3]}'],{$nShowlevel},{$nLevel},'{$sContainertype}','{$sDistrictPrefix}')";
				$sHtml.='<select name="'.$sDistrictPrefix.$arrElems[$nI].'" id="'.$arrElems[$nI].'" onchange="'.$sJscall.'">';
				$sHtml.='<option value="">'.self::getDistrictType($nLevel).'</option>';
				foreach($arrOptions[$nLevel] as $arrOption){
					$sSelected=$arrOption[0]==$arrValues[$nI]?' selected="selected"':'';
					$sHtml.='<option did="'.$arrOption[0].'" value="'.$arrOption[1].'"'.$sSelected.'>'.$arrOption[1].'</option>';
				}
				$sHtml.='</select>';
				$sHtml.='&nbsp;&nbsp;';
			}
		}

		return $sHtml;
	}

	public static function getDistrictByUpid($Upid,$sSort='DESC'){
		$oDistrict=Dyhb::instance('DistrictModel');

		return $oDistrict->getDistrictByUpid($Upid,$sSort);
	}

	public static function getDistrictType($nLevel){
		$sStr='';

		switch($nLevel){
			case 1:
				$sStr.=Dyhb::L('省份','__COMMON_LANG__@Function/Profile_Extend');
				break;
			case 2:
				$sStr.=Dyhb::L('城市','__COMMON_LANG__@Function/Profile_Extend');
				break;
			case 3:
				$sStr.=Dyhb::L('州县','__COMMON_LANG__@Function/Profile_Extend');
				break;
			case 4:
				$sStr.=Dyhb::L('乡镇','__COMMON_LANG__@Function/Profile_Extend');
				break;
		}

		return '-'.$sStr.'-';
	}

	public static function getGender($nGender){
		switch($nGender){
			case 0:
				return Dyhb::L('保密','__COMMON_LANG__@Function/Profile_Extend');
				break;
			case 1:
				return Dyhb::L('男','__COMMON_LANG__@Function/Profile_Extend');
				break;
			case 2:
				return Dyhb::L('女','__COMMON_LANG__@Function/Profile_Extend');
				break;
		}
	}

	public static function getInfoMenu(){
		$arrInfoMenus=array(
			''=>Dyhb::L('基本资料','__COMMON_LANG__@Function/Profile_Extend'),
			'contact'=>Dyhb::L('联系方式','__COMMON_LANG__@Function/Profile_Extend'),
			'edu'=>Dyhb::L('教育情况','__COMMON_LANG__@Function/Profile_Extend'),
			'work'=>Dyhb::L('工作状况','__COMMON_LANG__@Function/Profile_Extend'),
			'info'=>Dyhb::L('个人信息','__COMMON_LANG__@Function/Profile_Extend')
		);

		return $arrInfoMenus;
	}

	public static function getProfileSetting(){
		$arrBases=array('userprofile_realname','userprofile_gender','userprofile_birthday',
			'userprofile_birthcity','userprofile_residecity','userprofile_affectivestatus',
			'userprofile_lookingfor','userprofile_bloodtype');

		$arrContacts=array('userprofile_telephone','userprofile_mobile','userprofile_icq',
			'userprofile_qq','userprofile_yahoo','userprofile_msn','userprofile_taobao',
			'userprofile_google','userprofile_baidu','userprofile_renren','userprofile_douban',
			'userprofile_dianniu','userprofile_weibocom','userprofile_tqqcom',
			'userprofile_diandian','userprofile_facebook','userprofile_twriter','userprofile_skype');

		$arrEdus=array('userprofile_nowschool','userprofile_kindergarten','userprofile_primary',
			'userprofile_juniorhighschool','userprofile_highschool','userprofile_university',
			'userprofile_master','userprofile_dr','userprofile_graduateschool','userprofile_education');

		$arrWorks=array('userprofile_occupation','userprofile_company','userprofile_position','userprofile_revenue');

		$arrInfos=array('userprofile_idcardtype','userprofile_idcard','userprofile_address','userprofile_zipcode',
			'userprofile_site','userprofile_bio','userprofile_interest');

		return array($arrBases,$arrContacts,$arrEdus,$arrWorks,$arrInfos);
	}

	public static function formatUserinfo($sInfo){
		return nl2br(htmlspecialchars($sInfo));
	}

	public static function getUserprofilegender($nUserprofilegender){
		$sUsergender='';
		switch($nUserprofilegender){
			case '0':
				$sUsergender=__PUBLIC__.'/images/common/sex/secrecy.png';
				break;
			case '1':
				$sUsergender=__PUBLIC__.'/images/common/sex/male.png';
				break;
			case '2':
				$sUsergender=__PUBLIC__.'/images/common/sex/female.png';
				break;
		}
		
		return $sUsergender;
	}

	static public function checkPrivacy($nUserid,$nPrivacy=0){
		$nUserid=intval($nUserid);
		$nPrivacy=intval($nPrivacy);

		// 公开权限直接放行
		if($nPrivacy==0){
			return true;
		}
		
		if($GLOBALS['___login___']===false){
			return false;
		}

		// 自己 && 后台管理员
		$nLoginuserid=intval($GLOBALS['___login___']['user_id']);
		if($nUserid===$nLoginuserid || Core_Extend::isAdmin()){
			return true;
		}

		// 好友可见
		if($nPrivacy==1){
			$oTryfriend=FriendModel::F('user_id=? AND friend_friendid=? AND friend_status=1',$nUserid,$nLoginuserid)->getOne();

			if(!empty($oTryfriend['user_id'])){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

}
