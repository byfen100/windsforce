<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   提醒条数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class NewnoticenumController extends Controller{

	public function index(){
		header("Content-Type:text/html; charset=utf-8");
		
		$arrData=array();

		$nUserId=intval(G::getGpc('uid'));
		if(empty($nUserId)){
			$arrData=array('num'=>0);
		}else{
			$arrWhere['notice_isread']=0;
			$arrWhere['user_id']=$GLOBALS['___login___']['user_id'];

			$arrData['num']=intval(NoticeModel::F()->where($arrWhere)->all()->getCounts());
		}

		exit(json_encode($arrData));
	}

}
