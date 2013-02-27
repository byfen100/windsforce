<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组帖子模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GrouptopicModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'grouptopic',
			'props'=>array(
				'grouptopic_id'=>array('readonly'=>true),
				'user'=>array(Db::BELONGS_TO=>'UserModel','source_key'=>'user_id','target_key'=>'user_id'),
				'userprofile'=>array(Db::BELONGS_TO=>'UserprofileModel','source_key'=>'user_id','target_key'=>'user_id'),
				'usercount'=>array(Db::BELONGS_TO=>'UsercountModel','source_key'=>'user_id','target_key'=>'user_id'),
				'grouptopiccategory'=>array(Db::BELONGS_TO=>'GrouptopiccategoryModel','source_key'=>'grouptopiccategory_id','target_key'=>'grouptopiccategory_id','skip_empty'=>true),
				'group'=>array(Db::BELONGS_TO=>'GroupModel','source_key'=>'group_id','target_key'=>'group_id','skip_empty'=>true),
			),
			'attr_protected'=>'grouptopic_id',
			'autofill'=>array(
				array('user_id','userId','create','callback'),
				array('grouptopic_username','userName','create','callback'),
			),
			'check'=>array(
				'grouptopic_title'=>array(
					array('require',Dyhb::L('帖子标题不能为空','__APP_ADMIN_LANG__@Model/Grouptopic')),
					array('max_length',300,Dyhb::L('帖子标题不能超过300个字符','__APP_ADMIN_LANG__@Model/Grouptopic')),
				),
				'grouptopic_content'=>array(
					array('require',Dyhb::L('帖子内容不能为空','__APP_ADMIN_LANG__@Model/Grouptopic')),
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

		return $sUserName?$sUserName:Dyhb::L('佚名','Model/Grouptopic');
	}

}
