<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   滑动幻灯片模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SlideModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'slide',
			'check'=>array(
				'slide_title'=>array(
					array('require',Dyhb::L('幻灯片标题不能为空','__COMMON_LANG__@Model/Slide')),
					array('max_length',50,Dyhb::L('幻灯片标题最大长度为50个字符','__COMMON_LANG__@Model/Slide')),
				),
				'slide_img'=>array(
					array('require',Dyhb::L('幻灯片图片不能为空','__COMMON_LANG__@Model/Slide')),
					array('max_length',325,Dyhb::L('幻灯片图片最大长度为325个字符','__COMMON_LANG__@Model/Slide')),
				),
				'slide_url'=>array(
					array('require',Dyhb::L('幻灯片URL不能为空','__COMMON_LANG__@Model/Slide')),
					array('max_length',325,Dyhb::L('幻灯片URL最大长度为325个字符','__COMMON_LANG__@Model/Slide')),
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
		$_POST['slide_title']=G::html($_POST['slide_title']);
	}

}
