<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动感兴趣用户模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class EventattentionuserModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'eventattentionuser',
			'props'=>array(
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

}
