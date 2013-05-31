<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   手机版($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class MobileController extends Controller{

	public function index(){
		$this->display('public+mobile');
	}

	public function mobile_title_(){
		return Dyhb::L('手机版','Controller');
	}

	public function mobile_keywords_(){
		return $this->mobile_title_();
	}

	public function mobile_description_(){
		return $this->mobile_title_();
	}

}
