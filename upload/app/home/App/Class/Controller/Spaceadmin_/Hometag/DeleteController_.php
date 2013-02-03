<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   删除标签($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DeleteController extends Controller{

	public function index(){
		$nUserId=$GLOBALS['___login___']['user_id'];
		$nHometagId=intval(G::getGpc('id'));

		$oHometag=HometagindexModel::F('hometag_id=? AND user_id=?',$nHometagId,$nUserId)->getOne();
		if(!empty($oHometag['user_id'])){
			$oHometag->destroy();
		}

		$this->S(Dyhb::L('删除用户标签成功','Controller/Spaceadmin'));
	}

}
