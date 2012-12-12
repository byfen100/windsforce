<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   站点信息.联系我们($)*/

!defined('DYHB_PATH') && exit;

class ContactusController extends GlobalchildController{

	public $_oHomesite=null;
	
	public function index(){
		$this->_oParentcontroller->site_($this,'contactus');
	}

	public function contactus_title_(){
		return $this->_oHomesite['homesite_nikename'];
	}

	public function contactus_keywords_(){
		return $this->contactus_title_();
	}

	public function contactus_description_(){
		return $this->contactus_title_();
	}

}
