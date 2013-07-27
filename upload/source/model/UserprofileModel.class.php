<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户个人信息扩展模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UserprofileModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'userprofile',
			'props'=>array(
				'user'=>array(Db::HAS_ONE=>'UserModel','source_key'=>'user_id','target_key'=>'user_id'),
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

	static public function getUserprofileById($nUserId,$sField='userprofile_site'){
		$oUserprofile=UserprofileModel::F('user_id=?',$nUserId)->query();

		if(empty($oUserprofile['user_id'])){
			return null;
		}
		
		return $oUserprofile[$sField];
	}

}
