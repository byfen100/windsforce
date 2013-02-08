<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   短消息检测($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CheckpmController extends Controller{

	public function index(){
		$arrOptionData=$GLOBALS['_option_'];
		
		if($arrOptionData['pm_status']==0){
			$this->E(Dyhb::L('短消息功能尚未开启','Controller/Pm'));
		}
		
		if($arrOptionData['pmsend_regdays']>0){
			if(CURRENT_TIMESTAMP-$GLOBALS['___login___']['create_dateline']<86400*$arrOptionData['pmsend_regdays']){
				$this->E(Dyhb::L('只有注册时间超过 %d 天的用户才能够发送短消息','Controller/Pm',null,$arrOptionData['pmsend_regdays']));
			}
		}
		
		if($arrOptionData['pmflood_ctrl']>0){
			$nCurrentTimeStamp=CURRENT_TIMESTAMP;
			$nPmSpace=intval($arrOptionData['pmflood_ctrl']);
			
			$oPm=PmModel::F("pm_msgfromid=? AND {$nCurrentTimeStamp}-create_dateline<{$nPmSpace}",$GLOBALS['___login___']['user_id'])->query();
			if(!empty($oPm['pm_id'])){
				$this->E(Dyhb::L('每 %d 秒你才能发送一次短消息','Controller/Pm',null,$nPmSpace));
			}
		}
		
		if($arrOptionData['pmlimit_oneday']>0){
			$arrNowDate=Core_Extend::getBeginEndDay();
			
			$nPms=PmModel::F("create_dateline<{$arrNowDate[1]} AND create_dateline>{$arrNowDate[0]} AND pm_msgfromid=?",$GLOBALS['___login___']['user_id'])->all()->getCounts();
			if($nPms>$arrOptionData['pmlimit_oneday']){
				$this->E(Dyhb::L('一个用户每天最多只能发送 %d 条消息','Controller/Pm',null,$arrOptionData['pmlimit_oneday']));
			}
		}
	}

}
