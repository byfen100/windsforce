<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   网站地图($)*/

!defined('DYHB_PATH') && exit;

class SitemapController extends Controller{

	public function index(){
		$this->display('public+sitemap');
	}

}
