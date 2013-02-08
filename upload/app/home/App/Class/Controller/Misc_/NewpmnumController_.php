<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   短消息条数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class NewpmnumController extends Controller{

	public function index(){
		header("Content-Type:text/html; charset=utf-8");
		
		$arrData=array();

		$nUserId=intval(G::getGpc('uid'));
		if(empty($nUserId)){
			$arrData=array('system'=>0,'user'=>0,'total'=>0);
		}else{
			$arrWhere['pm_status']=1;

			// 私人短消息
			$arrWhere['pm_isread']=0;
			$arrWhere['pm_msgtoid']=$GLOBALS['___login___']['user_id'];
			$arrWhere['pm_type']='user';
			$arrData['user']=intval(PmModel::F()->where($arrWhere)->all()->getCounts());
			unset($arrWhere['pm_msgtoid']);

			// 系统短消息
			unset($arrWhere['pm_isread']);
			$arrWhere['pm_type']='system';

			// 需要排除的短消息ID
			$arrNotinPms=array();

			// 已删
			$arrSystemdeleteMessages=PmsystemdeleteModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getAll();
			if(is_array($arrSystemdeleteMessages)){
				foreach($arrSystemdeleteMessages as $oSystemdeleteMessage){
					$arrNotinPms[]=$oSystemdeleteMessage['pm_id'];
				}
			}

			// 已读
			$arrSystemreadMessages=PmsystemreadModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getAll();
			if(is_array($arrSystemreadMessages)){
				foreach($arrSystemreadMessages as $oSystemreadMessage){
					$arrNotinPms[]=$oSystemreadMessage['pm_id'];
				}
			}

			if(!empty($arrNotinPms)){
				$arrWhere['pm_id']=array('NOT IN',$arrNotinPms);
			}

			$arrData['system']=intval(PmModel::F()->where($arrWhere)->all()->getCounts());

			// 总共的短消息
			$arrData['total']=$arrData['system']+$arrData['user'];
		}

		exit(json_encode($arrData));
	}

}
