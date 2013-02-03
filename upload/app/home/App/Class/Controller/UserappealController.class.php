<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   用户申诉控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UserappealController extends InitController{

	public function index(){
		Core_Extend::doControllerAction('Userappeal@Index','index');
	}

	public function step2(){
		Core_Extend::doControllerAction('Userappeal@Step2','index',$this);
	}

	public function step3(){
		Core_Extend::doControllerAction('Userappeal@Step3','index',$this);
	}

	public function step4(){
		Core_Extend::doControllerAction('Userappeal@Step4','index',$this);
	}
	
	public function tocomputer(){
		Core_Extend::doControllerAction('Userappeal@Tocomputer','index');
	}

	public function tomail(){
		Core_Extend::doControllerAction('Userappeal@Tomail','index');
	}

	public function retrieve(){
		Core_Extend::doControllerAction('Userappeal@Retrieve','index');
	}

	public function schedule(){
		Core_Extend::doControllerAction('Userappeal@Schedule','index');
	}

	public function schedule_result(){
		Core_Extend::doControllerAction('Userappeal@Scheduleresult','index',$this);
	}

}
