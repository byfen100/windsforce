<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   广场随便看看($)*/

!defined('DYHB_PATH') && exit;

class ExploreController extends Controller{

	public function index(){
		$this->display('stat+explore');
	}

	public function explore_title_(){
		return Dyhb::L('随便看看','Controller/Stat');
	}

	public function explore_keywords_(){
		return $this->explore_title_();
	}

	public function explore_description_(){
		return $this->explore_title_();
	}
	
}
