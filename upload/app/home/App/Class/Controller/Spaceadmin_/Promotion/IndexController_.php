<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   访问推广($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		$this->assign('nUserId',Core_Extend::aidencode(intval($GLOBALS['___login___']['user_id'])));
		$this->assign('sUserName',rawurlencode(trim($GLOBALS['___login___']['user_name'])));
		$this->assign('sSiteName',$GLOBALS['_option_']['site_name']);
		$this->assign('sSiteUrl',$GLOBALS['_option_']['site_url']);

		$this->display('spaceadmin+promotion');
	}

	public function promotion_title_(){
		return Dyhb::L('访问推广','Controller/Spaceadmin');
	}

	public function promotion_keywords_(){
		return $this->promotion_title_();
	}

	public function promotion_description_(){
		return $this->promotion_title_();
	}

}
