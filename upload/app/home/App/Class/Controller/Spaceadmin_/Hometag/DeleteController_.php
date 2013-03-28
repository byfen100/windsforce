<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
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

		// 更新标签数量
		$oHometag=HometagModel::F('hometag_id=?',$nHometagId)->getOne();
		if(!empty($oHometag['hometag_id'])){
			$nTagIdCount=HometagindexModel::F('hometag_id=?',$nHometagId)->all()->getCounts();
			$oHometag->hometag_count=$nTagIdCount;
			$oHometag->save(0,'update');

			if($oHometag->isError()){
				$this->setErrorMessage($oHometag->getErrorMessage());
			}
		}

		$this->S(Dyhb::L('删除用户标签成功','Controller/Spaceadmin'));
	}

}
