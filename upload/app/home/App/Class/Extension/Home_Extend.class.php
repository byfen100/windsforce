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
		
		$arrActiveusers=UserModel::F()->where('user_status=?',1)->order('user_lastlogintime DESC')->limit(0,$nHomeactiveusernum)->getAll();
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

	public static function getOnlinedata(){
		$oDb=Db::RUN();

		// 查询在线统计
		$arrOnline=$oDb->getAllRows("SELECT k,COUNT(0) As countnum FROM(SELECT CASE WHEN user_id=0 THEN 'eq0' WHEN online_isstealth=1 THEN 'neq0s' ELSE 'neq0' END AS k FROM `windsforce_online`) AS temptable GROUP BY k");

		$nOnlineAllnum=$nOnlineGuestnum=$nOnlineUsernum=$nOnlineStealthusernum=0;

		foreach($arrOnline as $arrTemp){
			if($arrTemp['k']=='eq0'){
				$nOnlineGuestnum=intval($arrTemp['countnum']);
			}elseif($arrTemp['k']=='neq0s'){
				$nOnlineStealthusernum=intval($arrTemp['countnum']);
			}elseif($arrTemp['k']=='neq0'){
				$nOnlineUsernum=intval($arrTemp['countnum']);
			}
		}

		$nOnlineUsernum+=$nOnlineStealthusernum;
		$nOnlineAllnum=$nOnlineGuestnum+$nOnlineUsernum;

		// 保存最大在线用户数量
		if($nOnlineAllnum>$GLOBALS['_option_']['online_totalmostnum']){
			OptionModel::uploadOption('online_totalmostnum',$nOnlineAllnum);
			OptionModel::uploadOption('online_mosttime',CURRENT_TIMESTAMP);
		}

		// 首页的统计数据
		$arrOnlinedata['online_allnum']=$nOnlineAllnum;
		$arrOnlinedata['online_guestnum']=$nOnlineGuestnum;
		$arrOnlinedata['online_usernum']=$nOnlineUsernum;
		$arrOnlinedata['online_stealthusernum']=$nOnlineStealthusernum;
		$arrOnlinedata['online_totalmostnum']=$GLOBALS['_option_']['online_totalmostnum'];
		$arrOnlinedata['online_mosttime']=date('Y-m-d',$GLOBALS['_option_']['online_mosttime']);

		return $arrOnlinedata;
	}

}
