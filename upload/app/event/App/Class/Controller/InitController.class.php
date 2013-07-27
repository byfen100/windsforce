<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   公用控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class InitController extends GlobalinitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['_cache_']['event_option']['event_close']==1){
			$this->E(Dyhb::L('活动APP已经关闭，你无法使用。','Controller'));
		}
	}

}
