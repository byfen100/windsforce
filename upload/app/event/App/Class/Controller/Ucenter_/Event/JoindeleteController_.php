<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   删除我参加的活动($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class JoindeleteController extends Controller{

	public function index(){
		$arrEventid=G::getGpc('key');

		if($arrEventid){
			foreach($arrEventid as $nEventid){
				$oEventuser=EventuserModel::F('event_id=? AND user_id=?',$nEventid,$GLOBALS['___login___']['user_id'])->getOne();

				if(!empty($oEventuser['user_id'])){
					$oDb=Db::RUN();
					
					$sSql="DELETE FROM ".EventuserModel::F()->query()->getTablePrefix()."eventuser WHERE event_id={$nEventid} AND user_id={$GLOBALS['___login___']['user_id']}";

					$oDb->query($sSql);

					// 整理活动参加的数量
					$oEvent=EventModel::F('event_id=?',$nEventid)->getOne();
					if(!empty($oEvent['event_id'])){
						$oEvent->updateEventjoinnum($oEvent['event_id']);

						if($oEvent->isError()){
							$this->E($oEvent->getErrorMessage());
						}
					}
				}
			}
		}else{
			$this->E(Dyhb::L('你没有选择待删除的活动','Controller'));
		}

		$this->S(Dyhb::L('删除我参加的成功','Controller'));
	}

}
