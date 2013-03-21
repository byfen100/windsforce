<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   全局函数库文件($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Home_Extend{

	public static function getNewuser($nNum=null){
		if($nNum===null){
			$nHomenewusernum=intval($GLOBALS['_option_']['home_newuser_num']);
		}
		if($nHomenewusernum<1){
			$nHomenewusernum=1;
		}
		
		$arrNewusers=UserModel::F()->where('user_status=?',1)->order('create_dateline DESC')->limit(0,$nHomenewusernum)->getAll();
		return $arrNewusers;
	}

	public static function getActiveuser($nNum=null){
		if($nNum===null){
			$nHomeactiveusernum=intval($GLOBALS['_option_']['home_activeuser_num']);
		}
		if($nHomeactiveusernum<1){
			$nHomeactiveusernum=1;
		}
		
		$arrActiveusers=UserModel::F()->where('user_status=?',1)->order('update_dateline DESC')->limit(0,$nHomeactiveusernum)->getAll();
		return $arrActiveusers;
	}

	public static function getVisiteallowed($sType){
		if(Core_Extend::isAdmin()){
			return true;
		}else{
			if(isset($GLOBALS['_option_']['allowed_view_'.$sType])){
				$nVisiteallowed=intval($GLOBALS['_option_']['allowed_view_'.$sType]);

				if($nVisiteallowed==2){
						return false;
				}elseif($nVisiteallowed==1){
					if($GLOBALS['___login___']===false){
						return false;
					}else{
						return true;
					}
				}else{
					return true;
				}
			}else{
				return false;
			}
		}
	}

	public static function getHometagBydate($nDate,$nNum){
		$nData=intval($nDate);
		$nNum=intval($nNum);

		if($nNum<1){
			$nNum=1;
		}

		if($nDate<3600){
			$nDate=3600;
		}

		return HometagModel::F('create_dateline>?',1,(CURRENT_TIMESTAMP-$nDate))->order('hometag_count DESC')->limit(0,$nNum)->getAll();
	}

}
