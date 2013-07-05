<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加活动入库控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AddinController extends Controller{

	public function index(){
		$oEvent=new EventModel();
		$oEvent->formatTime();
		$oEvent->save(0);

		if($oEvent->isError()){
			$this->E($oEvent->getErrorMessage());
		}

		$this->S(Dyhb::L('活动添加成功','Controller'));
	}

}
