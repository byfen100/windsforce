<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动评论控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入杂项函数 */
require(Core_Extend::includeFile('function/Misc_Extend'));

class EventcommentController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['eventcomment_name']=array('like','%'.G::getGpc('eventcomment_name').'%');

		// 活动检索
		$nEid=intval(G::getGpc('eid','G'));
		if($nEid){
			$oEvent=EventModel::F('event_id=?',$nEid)->getOne();

			if(!empty($oEvent['event_id'])){
				$arrMap['event_id']=$nEid;
				$this->assign('oEvent',$oEvent);
			}
		}

		// 用户检索
		$nUid=intval(G::getGpc('uid','G'));
		if($nUid){
			$oUser=UserModel::F('user_id=?',$nUid)->getOne();

			if(!empty($oUser['user_id'])){
				$arrMap['user_id']=$nUid;
				$this->assign('oUser',$oUser);
			}
		}
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('eventcomment',false);

		$this->display(Admin_Extend::template('event','eventcomment/index'));
	}

	public function forbid($sModel=null,$sId=null,$bApp=false){
		$nId=G::getGpc('value');

		parent::forbid('eventcomment',$nId,true);
	}

	public function resume($sModel=null,$sId=null,$bApp=false){
		$nId=G::getGpc('value');

		parent::resume('eventcomment',$nId,true);
	}

	public function add(){
		$this->E(Dyhb::L('后台无法添加活动评论','__APPEVENT_COMMON_LANG__@Controller'));
	}

	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		parent::edit('eventcomment',$nId,false);
		$this->display(Admin_Extend::template('event','eventcomment/add'));
	}

	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::update('eventcomment',$nId);
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('value','G');

		$arrIds=explode(',',$sId);

		// 将活动评论子评论的父级ID改为当前的评论的父级ID(节点移位)
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				$oEventcomment=EventcommentModel::F('eventcomment_id=?',$nId)->getOne();

				if(!empty($oEventcomment['eventcomment_id'])){
					$arrEventchildcomments=EventcommentModel::F('eventcomment_parentid=?',$nId)->getAll();

					if(is_array($arrEventchildcomments)){
						foreach($arrEventchildcomments as $oEventchildcomment){
							$oEventchildcomment->eventcomment_parentid=$oEventcomment['eventcomment_parentid'];
							$oEventchildcomment->save(0,'update');

							if($oEventchildcomment->isError()){
								$this->E($oEventchildcomment->getErrorMessage());
							}
						}
					}
				}
			}
		}
	}

	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');

		$this->bForeverdelete_();
		
		parent::foreverdelete('eventcomment',$sId);
	}

	protected function aForeverdelete($sId){
		$sId=G::getGpc('value','G');

		$arrIds=explode(',',$sId);
		
		// 更新活动评论数量
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				// 更新评论数量
				$oEvent=Dyhb::instance('EventModel');
				$oEvent->updateEventcommentnum($nId);

				if($oEvent->isError()){
					$oEvent->getErrorMessage();
				}
			}
		}
	}
	
}
