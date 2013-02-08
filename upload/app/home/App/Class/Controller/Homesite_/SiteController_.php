<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   自定义站点信息($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SiteController extends GlobalchildController{

	public $_oHomesite=null;
	
	public function index(){
		$sName=G::text(G::getGpc('id','G'));
		$this->_oParentcontroller->site_($this,$sName);
	}

	public function site_title_(){
		return $this->_oHomesite['homesite_nikename'];
	}

	public function site_keywords_(){
		return $this->site_title_();
	}

	public function site_description_(){
		return $this->site_title_();
	}

}
