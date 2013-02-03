<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   标记阅读系统短消息($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ReadselectController extends Controller{

	public function index(){
		$arrPmIds=G::getGpc('pmid','P');
		
		if(empty($arrPmIds)){
			$this->E(Dyhb::L('你没有指定要标记的短消息','Controller/Pm'));
		}
		
		if($arrPmIds){
			$oPm=Dyhb::instance('PmModel');
			foreach($arrPmIds as $nPmId){
				$oPm->readSystemmessage($nPmId);

				if($oPm->isError()){
					$this->E($oPm->getErrorMessage());
				}
			}
		}
		
		$this->S(Dyhb::L('标记短消息已读成功','Controller/Pm'));
	}

}
