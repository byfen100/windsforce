<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class EventController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['event_title']=array('like','%'.G::getGpc('event_title').'%');

		// 活动类型
		$nCid=intval(G::getGpc('cid','G'));

		$oEventcategory=EventcategoryModel::F('eventcategory_id=?',$nCid)->getOne();
		if(!empty($oEventcategory['eventcategory_id'])){
			$arrMap['eventcategory_id']=$nCid;
			$this->assign('oEventcategory',$oEventcategory);
		}
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('event',false);

		$this->display(Admin_Extend::template('event','event/index'));
	}
	
	public function add(){
		$this->bAdd_();
		
		$this->display(Admin_Extend::template('event','event/add'));
	}
	
	public function bAdd_(){
		$oEventcategoryTree=Dyhb::instance('EventcategoryModel')->getEventcategoryTree();

		$this->assign('oEventcategoryTree',$oEventcategoryTree);
	}

	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		$this->bEdit_();
		
		parent::edit('event',$nId,false);
		$this->display(Admin_Extend::template('event','event/add'));
	}

	public function bEdit_(){
		$this->bAdd_();
	}

	protected function checktime_(){
		// 活动时间先验证
		$nEventstarttime=strtotime(trim(G::getGpc('event_starttime','P')));
		$nEventendtime=strtotime(trim(G::getGpc('event_endtime','P')));
		$nEventdeadline=strtotime(trim(G::getGpc('event_deadline','P')));

		if(!$nEventstarttime){
			$this->E(Dyhb::L('活动开始时间不能为空','__APPEVENT_COMMON_LANG__@Model'));
		}

		if(!$nEventendtime){
			$this->E(Dyhb::L('活动结束时间不能为空','__APPEVENT_COMMON_LANG__@Model'));
		}

		if(!$nEventdeadline){
			$this->E(Dyhb::L('活动报名截止时间不能为空','__APPEVENT_COMMON_LANG__@Model'));
		}

		if($nEventstarttime>$nEventendtime){
			$this->E(Dyhb::L('活动结束时间不能早于活动开始时间','__APPEVENT_COMMON_LANG__@Model'));
		}
		
		if($nEventdeadline<CURRENT_TIMESTAMP){
			$this->E(Dyhb::L('活动报名时间不能早于当前时间','__APPEVENT_COMMON_LANG__@Model'));
		}
		
		if($nEventdeadline>$nEventendtime){
			$this->E(Dyhb::L('活动报名时间不能晚于活动结束时间','__APPEVENT_COMMON_LANG__@Model'));
		}
	}

	public function insert($sModel=null,$nId=null){
		$nId=G::getGpc('value');

		$this->checktime_();
		
		parent::insert('event',$nId);
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
		$oModel->formatTime();
	}

	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		$this->checktime_();

		parent::update('event',$nId);
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();

		$_POST['event_starttime']=$nEventstarttime;
		$_POST['event_endtime']=$nEventendtime;
		$_POST['event_deadline']=$nEventdeadline;
	}

	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');

		parent::foreverdelete('event',$sId);
	}

	protected function aForeverdelete($sId){
		$sId=G::getGpc('value','G');

		$arrIds=explode(',',$sId);
		
		// 解除活动相关数据
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				// 删除关联数据(评论 && 感兴趣和参加数据)
				$oEventcommentMeta=EventcommentModel::M();
				$oEventcommentMeta->deleteWhere(array('event_id'=>$nId));

				if($oEventcommentMeta->isError()){
					$this->E($oEventcommentMeta->getErrorMessage());
				}

				$oEventuserMeta=EventuserModel::M();
				$oEventuserMeta->deleteWhere(array('event_id'=>$nId));

				if($oEventuserMeta->isError()){
					$this->E($oEventuserMeta->getErrorMessage());
				}

				$oEventattentionuserMeta=EventattentionuserModel::M();
				$oEventattentionuserMeta->deleteWhere(array('event_id'=>$nId));

				if($oEventattentionuserMeta->isError()){
					$this->E($oEventattentionuserMeta->getErrorMessage());
				}
			}
		}
	}

	public function forbid($sModel=null,$sId=null,$bApp=false){
		$nId=intval(G::getGpc('value','G'));

		parent::forbid('event',$nId,true);
	}

	public function resume($sModel=null,$sId=null,$bApp=false){
		$nId=intval(G::getGpc('value','G'));

		parent::resume('event',$nId,true);
	}

}
