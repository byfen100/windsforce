<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   公告模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AnnouncementModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'announcement',
			'props'=>array(
				'announcement_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'announcement_id',
			'autofill'=>array(
				array('announcement_username','userName','create','callback'),
			),
			'check'=>array(
				'announcement_sort'=>array(
					array('number',Dyhb::L('序号只能是数字','__COMMON_LANG__@Common')),
				),
				'announcement_title'=>array(
					array('require',Dyhb::L('公告标题不能为空','__COMMON_LANG__@Common')),
					array('max_length',225,Dyhb::L('公告标题最大长度为225','__COMMON_LANG__@Common'))
				),
				'announcement_message'=>array(
					array('require',Dyhb::L('公告内容不能为空','__COMMON_LANG__@Common')),
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

	public function timeFormat(){
		$_POST['create_dateline']=strtotime($_POST['create_dateline']);
		$_POST['announcement_endtime']=strtotime($_POST['announcement_endtime']);
	}

	public function safeInput(){
		$_POST['announcement_title']=G::html($_POST['announcement_title']);
		$_POST['announcement_message']=G::html($_POST['announcement_message']);
	}
	
	protected function userName(){
		$arrUserData=$GLOBALS['___login___'];

		return $arrUserData['user_name'];
	}
	
	public function deleteAllEndtime($nTimestamp){
		$oDb=Db::RUN();

		$oDb->query("DELETE FROM ".$this->getTablePrefix()."announcement WHERE announcement_endtime<".$nTimestamp." AND announcement_endtime<>'0'");
	}

}
