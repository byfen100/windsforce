<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   发送社会化登陆时间控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SocialoginController extends Controller{

	public function index(){
		$nTime=intval(G::getGpc('time'));

		if($nTime>0){
			Dyhb::cookie('SOCIA_LOGIN_TIME',$nTime);
		}

		$this->S(Dyhb::L('登陆COOKIE有效期','Controller/Misc').' '.$nTime.' (S)');
	}

}
