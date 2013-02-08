<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   站点信息.关于我们($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AboutusController extends GlobalchildController{

	public $_oHomesite=null;
	
	public function index(){
		$this->_oParentcontroller->site_($this,'aboutus');
	}

	public function aboutus_title_(){
		return $this->_oHomesite['homesite_nikename'];
	}

	public function aboutus_keywords_(){
		return $this->aboutus_title_();
	}

	public function aboutus_description_(){
		return $this->aboutus_title_();
	}

}
