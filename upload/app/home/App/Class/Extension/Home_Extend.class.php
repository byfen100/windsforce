<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   全局函数库文件($)*/

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

}
