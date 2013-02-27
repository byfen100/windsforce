<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   查询申诉页面($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ScheduleController extends GlobalchildController{

	public function index(){
		if(UserModel::M()->isLogin()){
			$this->U('home://ucenter/index');
		}

		$this->display('userappeal+schedule');
	}

	public function schedule_title_(){
		return Dyhb::L('查询申诉进度','Controller/Userappeal');
	}

	public function schedule_keywords_(){
		return $this->schedule_title_();
	}

	public function schedule_description_(){
		return $this->schedule_title_();
	}

}
