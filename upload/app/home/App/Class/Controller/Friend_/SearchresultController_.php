<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   好友搜索结果($)*/

!defined('DYHB_PATH') && exit;

class SearchresultController extends Controller{

	public function index(){
		require_once(Core_Extend::includeFile('function/Profile_Extend'));
		
		// 扩展信息字段
		Core_Extend::loadCache('userprofilesetting');

		$arrFields=array();
		foreach($GLOBALS['_cache_']['userprofilesetting'] as $nKey=>$arrValue){
			if($arrValue['userprofilesetting_title'] && $arrValue['userprofilesetting_status']==1 && $arrValue['userprofilesetting_allowsearch']==1){
				$arrFields[$arrValue['userprofilesetting_id']]=$arrValue;
			}
		}

		$nNowyear=date('Y',CURRENT_TIMESTAMP);

		// 初始化数据库查询条件
		$arrWhere=$arrFrom=array();
		$sSql='';

		// 用户表
		$arrFrom['user']=UserModel::F()->query()->getTablePrefix().'user as u';

		// 用户ID,用户名，头像情况
		foreach(array('user_id','user_name') as $sValue){
			if($_GET[$sValue]){
				if($sValue=='user_name' && empty($_GET['username_precision'])){
					$_GET[$sValue]=strip_tags($_GET[$sValue]);
					$arrWhere[]='u.'.$sValue.' LIKE '.'"%'.$_GET[$sValue].'%"';
				}else{
					$arrWhere[]='u.'.$sValue.'="'.$_GET[$sValue].'"';
				}
			}
		}

		// 年龄段
		$nUserstartage=$nUserendage=0;
		if($_GET['user_endage']){
			$nUserstartage=$nNowyear-intval($_GET['user_endage']);
		}
		if($_GET['user_startage']){
			$nUserendage=$nNowyear-intval($_GET['user_startage']);
		}

		if($nUserstartage && $nUserendage && $nUserendage>$nUserstartage){
			$arrWhere[]='up.userprofile_birthyear>='.$nUserstartage.' AND up.userprofile_birthyear<='.$nUserendage;
		}elseif($nUserstartage && empty($nUserendage)){
			$arrWhere[]='up.userprofile_birthyear>='.$nUserstartage;
		}elseif(empty($nUserstartage) && $nUserendage){
			$arrWhere[]='up.userprofile_birthyear<='.$nUserendage;
		}

		// 扩展字段查询条件
		$bHavefield=FALSE;

		foreach($arrFields as $sKey=>$arrValue){
			$_GET[$sKey]=empty($_GET[$sKey])?'':strip_tags($_GET[$sKey]);
			if($_GET[$sKey]){
				$bHavefield=TRUE;
				$arrWhere[]='up.'.$sKey.'  LIKE '.'"%'.$_GET[$sKey].'%"';
			}
		}

		if($bHavefield || $nUserstartage || $nUserendage) {
			$arrFrom['userprofile']=UserprofileModel::F()->query()->getTablePrefix().'userprofile as up';
			$arrWhere['userprofile']="up.user_id=u.user_id";
		}

		$arrUsers=array();
		if($arrWhere){
			$oDb=Db::RUN();

			$arrCount=$oDb->getRow("SELECT COUNT(*) AS row_count FROM ".implode(',',$arrFrom)." WHERE ".implode(' AND ',$arrWhere));
			$nTotalRecord=$arrCount['row_count'];

			$oPage=Page::RUN($nTotalRecord,10,G::getGpc('page','G'));
			
			$sSql="SELECT u.* FROM ".implode(',',$arrFrom)." WHERE ".implode(' AND ',$arrWhere).' LIMIT '.$oPage->returnPageStart().','.'10';
			$arrUsers=$oDb->getAllRows($sSql);

			$this->assign('nTotalUser',$nTotalRecord);
			$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		}

		$this->assign('arrUsers',$arrUsers);

		$this->display('friend+searchresult');
	}

	public function get_gender($arrUser){
		$oUserprofile=UserprofileModel::F('user_id=?',$arrUser['user_id'])->getOne();

		if(!empty($oUserprofile['user_id'])){
			$nGender=$oUserprofile['userprofile_gender'];
		}else{
			$nGender=0;
		}

		return Profile_Extend::getGender($nGender);
	}

}