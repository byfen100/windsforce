<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   热门话题($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TopicController extends Controller{

	public function index(){
		$nHomefreshdialoghottagnum=intval($GLOBALS['_cache_']['home_option']['homefresh_dialoghottagnum']);
		if($nHomefreshdialoghottagnum<1){
			$nHomefreshdialoghottagnum=1;
		}

		$nDate=intval($GLOBALS['_cache_']['home_option']['home_hothomefreshtag_date']);
		if($nDate<3600){
			$nData=3600;
		}
		
		// 读取热门话题
		$arrHothomefreshtags=HomefreshtagModel::F('homefreshtag_status=? AND create_dateline>?',1,CURRENT_TIMESTAMP-$nDate)->order('homefreshtag_totalcount DESC')->limit(0,$nHomefreshdialoghottagnum)->getAll();
		$this->assign('arrHothomefreshtags',$arrHothomefreshtags);
		
		$this->display('homefresh+topic');
	}

}
