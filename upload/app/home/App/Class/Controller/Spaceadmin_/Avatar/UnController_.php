<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   删除头像($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UnController extends Controller{

	public function index(){
		require_once(Core_Extend::includeFile('function/Avatar_Extend'));
		
		try{
			Avatar_Extend::deleteAvatar();
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		$this->S(Dyhb::L('删除头像成功了','Controller'));
	}

}
