<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   用户申诉函数库文件($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Userappeal_Extend{

	public static function getProgress(){
		if(ACTION_NAME==='index'){
			return 25;
		}elseif(ACTION_NAME==='step2'){
			return 50;
		}elseif(ACTION_NAME==='step3'){
			return 75;
		}elseif(ACTION_NAME==='step4'){
			return 100;
		}

		return 0;
	}

	public static function scheduleProgress($nProgress){
		if($nProgress==0){
			return 33;
		}elseif($nProgress==1){
			return 66;
		}elseif($nProgress==2 || $nProgress==3){
			return 100;
		}

		return 0;
	}

}
