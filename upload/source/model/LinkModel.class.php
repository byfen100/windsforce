<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   友情链接模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class LinkModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'link',
			'props'=>array(
				'link_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'link_id',
			'check'=>array(
				'link_sort'=>array(
					array('number',Dyhb::L('序号只能是数字','__COMMON_LANG__@Model/Common')),
				),
				'link_name'=>array(
					array('require',Dyhb::L('链接名字不能为空','__COMMON_LANG__@Model/Link')),
					array('max_length',32,Dyhb::L('链接名字最大长度为32','__COMMON_LANG__@Model/Link'))
				),
				'link_url'=>array(
					array('require',Dyhb::L('链接URL 不能为空','__COMMON_LANG__@Model/Link')),
					array('max_length',250,Dyhb::L('链接Url 最大长度为250','__COMMON_LANG__@Model/Link')),
				),
				'link_logo'=>array(
					array('empty'),
					array('max_length',360,Dyhb::L('链接Logo 最大长度为360','__COMMON_LANG__@Model/Link')),
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
		$_POST['link_name']=G::html($_POST['link_name']);
		$_POST['link_description']=G::html($_POST['link_description']);
	}

}
