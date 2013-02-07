<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   积分变更记录($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CreditlogController extends Controller{

	public function index(){
		$this->display('spaceadmin+creditlog');
	}

	public function creditlog_title_(){
		return Dyhb::L('积分记录','Controller/Spaceadmin');
	}

	public function creditlog_keywords_(){
		return $this->creditlog_title_();
	}

	public function creditlog_description_(){
		return $this->creditlog_title_();
	}

}
