<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   后台快捷菜单模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AdminctrlmenuModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'adminctrlmenu',
			'props'=>array(
				'adminctrlmenu_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'adminctrlmenu_id',
			'autofill'=>array(
				array('user_id','userid','create','callback'),
				array('adminctrlmenu_admin','admin','create','callback'),
			),
			'check'=>array(
				'adminctrlmenu_title'=>array(
					array('require',Dyhb::L('快捷导航标题不能为空','__COMMON_LANG__@Common')),
					array('max_length',50,Dyhb::L('快捷导航标题最大长度为50个字符','__COMMON_LANG__@Common')),
				),
				'adminctrlmenu_url'=>array(
					array('require',Dyhb::L('快捷导航URL地址不能为空','__COMMON_LANG__@Common')),
					array('max_length',255,Dyhb::L('快捷导航URL地址最大长度为255个字符','__COMMON_LANG__@Common')),
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
		$_POST['adminctrlmenu_title']=G::html($_POST['adminctrlmenu_title']);
	}

	public function userid(){
		return $GLOBALS['___login___']['user_id'];
	}	
	
	public function admin(){
		return $GLOBALS['___login___']['user_name'];
	}

}
