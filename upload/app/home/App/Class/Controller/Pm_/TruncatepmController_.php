<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   对话框发送短消息($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TruncatepmController extends Controller{

	public function index(){
		$nUserId=G::getGpc('uid','G');
		
		$sDate=G::getGpc('date','G');
		if(empty($sDate)){
			$sDate=3;
		}
		
		$oDb=Db::RUN();
		$sSql="UPDATE ".PmModel::F()->query()->getTablePrefix().
			"pm SET pm_status=0 WHERE `pm_msgfromid`={$nUserId} AND `pm_status`=1 AND `pm_msgtoid`=".
			$GLOBALS['___login___']['user_id'].
			($sDate!='all'?" AND `create_dateline`>=".(CURRENT_TIMESTAMP-$sDate*86400):'');
		$oDb->query($sSql);
		
		$this->assign('__JumpUrl__',Dyhb::U('home://pm/index?type=user'));
		$this->S(Dyhb::L('短消息清空成功','Controller'));
	}

}
