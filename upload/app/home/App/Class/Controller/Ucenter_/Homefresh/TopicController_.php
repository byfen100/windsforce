<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   热门话题($)*/

!defined('DYHB_PATH') && exit;

class TopicController extends Controller{

	public function index(){
		$nHomefreshdialoghottagnum=intval($GLOBALS['_cache_']['home_option']['homefresh_dialoghottagnum']);
		if($nHomefreshdialoghottagnum<1){
			$nHomefreshdialoghottagnum=1;
		}
		
		// 读取热门话题
		$arrHothomefreshtags=HomefreshtagModel::F('homefreshtag_status=?',1)->order('homefreshtag_totalcount DESC')->limit(0,$nHomefreshdialoghottagnum)->getAll();
		$this->assign('arrHothomefreshtags',$arrHothomefreshtags);
		
		$this->display('homefresh+topic');
	}

}
