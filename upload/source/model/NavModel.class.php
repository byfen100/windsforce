<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   菜单模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class NavModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'nav',
			'props'=>array(
				'nav_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'nav_id',
			'check'=>array(
				'nav_name'=>array(
					array('require',Dyhb::L('导航条名字不能为空','__COMMON_LANG__@Common')),
					array('max_length',32,Dyhb::L('导航条名字最大长度为32','__COMMON_LANG__@Common'))
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

	public function customIdentifier(){
		$this->nav_identifier='custom_'.G::randString(6);
	}

	public function safeInput(){
		$_POST['nav_name']=G::html($_POST['nav_name']);
		$_POST['nav_title']=G::html($_POST['nav_title']);
		$_POST['nav_url']=G::html($_POST['nav_url']);
	}

}
