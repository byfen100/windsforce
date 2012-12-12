<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   删除系统短消息($)*/

!defined('DYHB_PATH') && exit;

class DeletesystempmController extends Controller{

	public function index(){
		$arrPmIds=G::getGpc('pmid','P');

		if(is_array($arrPmIds)){
			$oPm=Dyhb::instance('PmModel');
			foreach($arrPmIds as $nPmId){
				$oPm->deleteSystemmessage($nPmId);

				if($oPm->isError()){
					$this->E($oPm->getErrorMessage());
				}
			}
		}

		$this->S(Dyhb::L('删除系统短消息成功','Controller/Pm'));
	}

}
