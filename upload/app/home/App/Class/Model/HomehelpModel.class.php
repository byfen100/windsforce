<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   网站帮组模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HomehelpModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'homehelp',
			'props'=>array(
				'homehelp_id'=>array('readonly'=>true),
				'homehelpcategory'=>array(Db::BELONGS_TO=>'HomehelpcategoryModel','source_key'=>'homehelp_id','target_key'=>'homehelpcategory_id','skip_empty'=>true),
			),
			'attr_protected'=>'homehelp_id',
			'autofill'=>array(
				array('user_id','userId','create','callback'),
				array('homehelp_username','userName','create','callback'),
				array('homehelp_updateuserid','userId','update','callback'),
				array('homehelp_updateusername','userName','update','callback'),
			),
			'check'=>array(
				'homehelp_title'=>array(
					array('require',Dyhb::L('帮助标题不能为空','__APPHOME_COMMON_LANG__@Model')),
					array('max_length',250,Dyhb::L('帮助标题不能超过250个字符','__APPHOME_COMMON_LANG__@Model')),
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
		if(isset($_POST['homehelp_title'])){
			$_POST['homehelp_title']=G::html($_POST['homehelp_title']);
		}
	}

	protected function userId(){
		$arrUserData=$GLOBALS['___login___'];

		return $arrUserData['user_id'];
	}

	protected function userName(){
		$arrUserData=$GLOBALS['___login___'];

		return $arrUserData['user_name'];
	}

}
