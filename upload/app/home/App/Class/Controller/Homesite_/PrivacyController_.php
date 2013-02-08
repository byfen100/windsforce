<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   站点信息.隐私声明($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class PrivacyController extends GlobalchildController{

	public $_oHomesite=null;
	
	public function index(){
		$this->_oParentcontroller->site_($this,'privacy');
	}

	public function privacy_title_(){
		return $this->_oHomesite['homesite_nikename'];
	}

	public function privacy_keywords_(){
		return $this->privacy_title_();
	}

	public function privacy_description_(){
		return $this->privacy_title_();
	}

}
