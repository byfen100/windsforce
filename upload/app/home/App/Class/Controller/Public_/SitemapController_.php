<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   网站地图($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SitemapController extends Controller{

	public function index(){
		$this->display('public+sitemap');
	}

	public function sitemap_title_(){
		return Dyhb::L('网站地图','Controller');
	}

	public function sitemap_keywords_(){
		return $this->sitemap_title_();
	}

	public function sitemap_description_(){
		return $this->sitemap_title_();
	}

}
