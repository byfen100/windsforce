<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户排行缓存($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UpdateCacheUsertop{

	public static function cache(){
		$arrData=array();

		if(!isset($GLOBALS['_cache_']['home_option'])){
			Core_Extend::loadCache('home_option');
		}

		$nTopusernum=$GLOBALS['_cache_']['home_option']['topuser_num'];
		if($nTopusernum<1){
			$nTopusernum=1;
		}

		// 活跃用户
		$arrData['active']=self::getActiveuser($nTopusernum);

		// 最新加入会员
		$arrData['new']=self::getNewuser($nTopusernum);

		// 会员积分排行
		$arrData['credit']=self::getCredituser($nTopusernum);

		// 会员粉丝排行
		$arrData['fan']=self::getFanuser($nTopusernum);

		// 会员在线时间
		$arrData['oltime']=self::getOltimeuser($nTopusernum);

		Core_Extend::saveSyscache('usertop',$arrData);
	}

	protected static function getActiveuser($nTopusernum){
		$arrData=array();
		
		$arrActiveusers=UserModel::F('user_status=?',1)->order('user_lastlogintime DESC')->limit(0,$nTopusernum)->getAll();
		
		if(is_array($arrActiveusers)){
			foreach($arrActiveusers as $oUser){
				$arrData[]=array(
					'user_id'=>$oUser['user_id'],
					'user_name'=>$oUser['user_name'],
					'user_nikename'=>$oUser['user_nikename'],
					'user_lastlogintime'=>$oUser['user_lastlogintime'],
					'create_dateline'=>$oUser['create_dateline'],
				);
			}
		}

		return $arrData;
	}

	protected static function getNewuser($nTopusernum){
		$arrData=array();
		
		$arrNewusers=UserModel::F('user_status=?',1)->order('create_dateline DESC')->limit(0,$nTopusernum)->getAll();
		
		if(is_array($arrNewusers)){
			foreach($arrNewusers as $oUser){
				$arrData[]=array(
					'user_id'=>$oUser['user_id'],
					'user_name'=>$oUser['user_name'],
					'user_nikename'=>$oUser['user_nikename'],
					'create_dateline'=>$oUser['create_dateline'],
				);
			}
		}

		return $arrData;
	}

	protected static function getCredituser($nTopusernum){
		return self::getUserorder_('usercount_extendcredit1',$nTopusernum);
	}

	protected static function getFanuser($nTopusernum){
		return self::getUserorder_('usercount_fans',$nTopusernum);
	}

	protected static function getOltimeuser($nTopusernum){
		return self::getUserorder_('usercount_oltime',$nTopusernum);
	}

	protected static function getUserorder_($sType,$nTopusernum){
		$arrData=array();
		$arrUsers=array();

		$arrUsercounts=UsercountModel::F()->order($sType.' DESC')->limit(0,$nTopusernum)->getAll();
		if(is_array($arrUsercounts)){
			foreach($arrUsercounts as $oUsercount){
				$arrUsers[]=UserModel::F('user_id=? AND user_status=1',$oUsercount['user_id'])->getOne();
			}
		}
		
		if($arrUsers){
			foreach($arrUsers as $oUser){
				$arrData[]=array(
					'user_id'=>$oUser['user_id'],
					'user_name'=>$oUser['user_name'],
					'user_nikename'=>$oUser['user_nikename'],
					'create_dateline'=>$oUser['create_dateline'],
					'data'=>$oUser->usercount->{$sType},
				);
			}
		}

		return $arrData;
	}

}
