<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组喜欢帖子数据模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GrouptopicloveModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'grouptopiclove',
			'props'=>array(
				'grouptopiclove_id'=>array('readonly'=>true),
				'user'=>array(Db::BELONGS_TO=>'UserModel','source_key'=>'user_id','target_key'=>'user_id'),
				'grouptopic'=>array(Db::BELONGS_TO=>'GrouptopicModel','source_key'=>'grouptopic_id','target_key'=>'grouptopic_id','skip_empty'=>true),
			),
			'attr_protected'=>'grouptopiclove_id',
			'autofill'=>array(
				array('user_id','userId','create','callback'),
				array('grouptopiclove_username','userName','create','callback'),
			),
			'check'=>array(
				'grouptopiclove_title'=>array(
					array('require',Dyhb::L('喜欢备注不能为空','__APPGROUP_COMMON_LANG__@Model/Grouptopiclove')),
					array('max_length',300,Dyhb::L('喜欢备注不能超过300个字符','__APPGROUP_COMMON_LANG__@Model/Grouptopiclove')),
				),
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

	public function safeInput(){
	}

	protected function userId(){
		$nUserId=$GLOBALS['___login___']['user_id'];

		return $nUserId>0?$nUserId:0;
	}	
	
	protected function userName(){
		$sUserName=$GLOBALS['___login___']['user_name'];

		return $sUserName?$sUserName:'';
	}

}
