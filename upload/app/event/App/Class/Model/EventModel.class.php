<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class EventModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'event',
			'props'=>array(
				'event_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'event_id',
			'autofill'=>array(
				array('user_id','userId','create','callback'),
				array('event_username','userName','create','callback'),
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
		$arrUserData=$GLOBALS['___login___'];

		return $arrUserData['user_id']?$arrUserData['user_id']:0;
	}

	protected function userName(){
		$arrUserData=$GLOBALS['___login___'];

		return $arrUserData['user_name']?$arrUserData['user_name']:'';
	}

	public function formatTime(){
		$this->event_starttime=strtotime($_POST['event_starttime']);
		$this->event_endtime=strtotime($_POST['event_endtime']);
		$this->event_deadline=strtotime($_POST['event_deadline']);
	}

	public function updateEventcommentnum($nEventid){
		$nEventid=intval($nEventid);

		$oEvent=EventModel::F('event_id=?',$nEventid)->getOne();
		if(!empty($oEvent['event_id'])){
			$nEventcommentnum=EventcommentModel::F('eventcomment_status=1 AND eventcomment_auditpass=1 AND event_id=?',$nEventid)->all()->getCounts();

			$oEvent->event_commentcount=$nEventcommentnum;
			$oEvent->save(0,'update');

			if($oEvent->isError()){
				$this->setErrorMessage($oEvent->getErrorMessage());
				return false;
			}
		}

		return true;
	}

	public function updateEventjoinnum($nEventid){
		$nEventid=intval($nEventid);

		$oEvent=EventModel::F('event_id=?',$nEventid)->getOne();
		if(!empty($oEvent['event_id'])){
			$nEventjoinnum=EventuserModel::F('eventuser_admin=0 AND event_id=?',$nEventid)->all()->getCounts();

			$oEvent->event_joincount=$nEventjoinnum;
			$oEvent->save(0,'update');

			if($oEvent->isError()){
				$this->setErrorMessage($oEvent->getErrorMessage());
				return false;
			}
		}

		return true;
	}

	public function updateEventattentionnum($nEventid){
		$nEventid=intval($nEventid);

		$oEvent=EventModel::F('event_id=?',$nEventid)->getOne();
		if(!empty($oEvent['event_id'])){
			$nEventattentionnum=EventattentionuserModel::F('event_id=?',$nEventid)->all()->getCounts();

			$oEvent->event_attentioncount=$nEventattentionnum;
			$oEvent->save(0,'update');

			if($oEvent->isError()){
				$this->setErrorMessage($oEvent->getErrorMessage());
				return false;
			}
		}

		return true;
	}

}
