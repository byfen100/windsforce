<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   删除一条短消息($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DelonepmController extends Controller{

	public function index($nId='',$nUserId='',$nFromId=''){
		$nOldId=$nId;
		
		if(empty($nId)){
			$nId=G::getGpc('id');
		}
		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定要删除的短消息','Controller/Pm'));
		}
		
		if(empty($nUserId)){
			$nUserId=$GLOBALS['___login___']['user_id'];
		}
		
		if(empty($nFromId) && $nFromId!==0){
			$nFromId=G::getGpc('uid');;
		}
		
		$oPmModel=PmModel::F("pm_id=? AND pm_type='user' AND pm_msgtoid=? ".($nFromId!==0?'AND pm_msgfromid='.$nFromId:''),$nId,$nUserId)->query();
		if(empty($oPmModel['pm_id'])){
			$this->E(Dyhb::L('待删除的短消息不存在','Controller/Pm'));
		}
		
		$oPmModel->pm_status=0;
		$oPmModel->save(0,'update');
		
		if($oPmModel->isError()){
			$this->E($oPmModel->getErrorMessage());
		}else{
			if(empty($nOldId)){
				$this->S(Dyhb::L('删除短消息成功','Controller/Pm'));
			}
		}
	}

	public function select(){
		$arrPmIds=G::getGpc('pmid','P');
		$arrUserId=G::getGpc('uid','P');
		
		if(empty($arrPmIds)){
			$this->E(Dyhb::L('你没有指定要删除的短消息','Controller/Pm'));
		}
		
		if(is_array($arrPmIds)){
			foreach($arrPmIds as $nPmId){
				$this->index($nPmId,$GLOBALS['___login___']['user_id'],0);
			}
		}
		
		$this->S(Dyhb::L('删除短消息成功','Controller/Pm'));
	}

}
