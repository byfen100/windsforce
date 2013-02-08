<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   站点信息.用户协议($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AgreementController extends GlobalchildController{

	public $_oHomesite=null;
	
	public function index(){
		$this->_oParentcontroller->site_($this,'agreement');
	}

	public function agreement_title_(){
		return $this->_oHomesite['homesite_nikename'];
	}

	public function agreement_keywords_(){
		return $this->agreement_title_();
	}

	public function agreement_description_(){
		return $this->agreement_title_();
	}

}
