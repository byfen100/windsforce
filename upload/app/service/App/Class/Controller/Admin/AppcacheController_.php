<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   服务缓存控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AppcacheController extends InitController{

	public function index($sModel=null,$bDisplay=true){
		// 更新缓存
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::appUpdateCache('','service');
		
		$this->assign('__JumpUrl__',Dyhb::U('globalcache/index'));

		$this->S(Dyhb::L('缓存更新成功','Controller'));
	}

}
