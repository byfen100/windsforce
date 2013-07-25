<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   编辑活动控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class EditController extends Controller{

	protected $_oEvent=null;
	
	public function index(){
		$nEventid=intval(G::getGpc('id','G'));

		if(empty($nEventid)){
			$this->E(Dyhb::L('你没有指定活动ID','Controller'));
		}

		$oEvent=EventModel::F('event_status=1 AND event_id=?',$nEventid)->getOne();
		if(empty($oEvent['event_id'])){
			$this->E(Dyhb::L('你要编辑的活动不存在','Controller'));
		}

		// 判断权限
		if(!Eventadmin_Extend::checkEvent($oEvent)){
			$this->E(Dyhb::L('你没有权限编辑活动','Controller'));
		}

		$this->_oEvent=$oEvent;

		$this->assign('oEvent',$oEvent);
		
		// 活动类型
		$oEventcategoryTree=Dyhb::instance('EventcategoryModel')->getEventcategoryTree();
		$this->assign('oEventcategoryTree',$oEventcategoryTree);
		
		$this->display('event+add');
	}

	public function edit_title_(){
		return $this->_oEvent['event_title'].' - '.Dyhb::L('编辑活动','Controller');
	}

	public function edit_keywords_(){
		return $this->edit_title_();
	}

	public function edit_description_(){
		return $this->edit_title_();
	}

}
