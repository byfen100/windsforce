<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   应用模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AppModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'app',
			'props'=>array(
				'app_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'app_id',
			'check'=>array(
				'app_name'=>array(
					array('require',Dyhb::L('应用名不能为空','__COMMON_LANG__@Common')),
					array('max_length',32,Dyhb::L('应用名最大长度为32个字符','__COMMON_LANG__@Common')),
				),
				'app_identifier'=>array(
					array('require',Dyhb::L('应用唯一识别符不能为空','__COMMON_LANG__@Common')),
					array('english',Dyhb::L('应用唯一识别符只能为英文字符','__COMMON_LANG__@Common')),
					array('max_length',32,Dyhb::L('应用唯一识别符最大长度为32个字符','__COMMON_LANG__@Common')),
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

	public function filterAppindentifier(){
		$this->app_identifier=str_replace(array('_','-'),array('',''),$this->app_identifier);
	}

	public function safeInput(){
		$_POST['app_identifier']=strtolower(G::html($_POST['app_identifier']));
		$_POST['app_name']=G::html($_POST['app_name']);
		$_POST['app_author']=G::html($_POST['app_author']);

		if(isset($_POST['app_version'])){
			$_POST['app_version']=G::html($_POST['app_version']);
		}
	}

}
