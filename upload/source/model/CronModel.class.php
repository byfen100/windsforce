<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   计划任务模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CronModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'cron',
			'props'=>array(
				'cron_id'=>array('readonly'=>true),
			),
			'check'=>array(
				'cron_name'=>array(
					array('require',Dyhb::L('计划任务名不能为空','__COMMON_LANG__@Model/Cron')),
					array('max_length',50,Dyhb::L('计划任务名最大长度为50个字符','__COMMON_LANG__@Model/Cron')),
				),
				'on_update'=>array(
					'cron_filename'=>array(
						array('require',Dyhb::L('计划任务脚本不能为空','__COMMON_LANG__@Model/Cron')),
						array('max_length',50,Dyhb::L('计划任务脚本最大长度为50个字符','__COMMON_LANG__@Model/Cron')),
					),
					'cron_minute'=>array(
						array('max_length',36,Dyhb::L('计划任务分钟最大长度为36个字符','__COMMON_LANG__@Model/Cron')),
					),
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

	public function fetchNextrun($nTimestamp){
		$nTimestamp=intval($nTimestamp);
		return self::F('cron_status=1 AND cron_nextrun<=?',$nTimestamp)->order('cron_nextrun')->getOne();
	}

	public function fetchNextcron(){
		return self::F('cron_status=?',1)->order('cron_nextrun')->getOne();
	}

}
