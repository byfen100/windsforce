<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   热门话题($)*/

!defined('DYHB_PATH') && exit;

class TopicController extends Controller{

	public function index(){
		$this->display('homefresh+topic');
	}

}
