<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   站点统计缓存($)*/

!defined('DYHB_PATH') && exit;

class UpdateCacheSite{

	public static function cache(){
		$arrData=array();

		$arrData['homefresh']=HomefreshModel::F()->all()->getCounts();
		$arrData['homefreshcomment']=HomefreshcommentModel::F()->all()->getCounts();
		$arrData['attachment']=AttachmentModel::F()->all()->getCounts();
		$arrData['app']=AppModel::F()->all()->getCounts();
		$arrData['user']=UserModel::F()->all()->getCounts();
		$arrData['adminuser']=UserModel::F()->where(array('user_id'=>array('IN',Dyhb::C('ADMIN_USERID'))))->all()->getCounts();

		$nCurrentTimeStamp=CURRENT_TIMESTAMP;
		$arrData['newuser']=UserModel::F("{$nCurrentTimeStamp}-create_dateline<86400")->all()->getCounts();

		Core_Extend::saveSyscache('site',$arrData);
	}

}
