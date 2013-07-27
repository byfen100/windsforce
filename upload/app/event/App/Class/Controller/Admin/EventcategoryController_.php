<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动类型控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class EventcategoryController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['eventcategory_name']=array('like','%'.G::getGpc('eventcategory_name').'%');
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('eventcategory',false);

		$this->display(Admin_Extend::template('event','eventcategory/index'));
	}
	
	public function add(){
		$this->bAdd_();
		
		$this->display(Admin_Extend::template('event','eventcategory/add'));
	}
	
	public function bAdd_(){
		$oEventcategoryTree=Dyhb::instance('EventcategoryModel')->getEventcategoryTree();

		$this->assign('oEventcategoryTree',$oEventcategoryTree);
	}

	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		$this->bEdit_();
		
		parent::edit('eventcategory',$nId,false);
		$this->display(Admin_Extend::template('event','eventcategory/add'));
	}

	public function bEdit_(){
		$this->bAdd_();
	}

	public function insert($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::insert('eventcategory',$nId);
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}

	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::update('eventcategory',$nId);
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');

		$this->bForeverdelete_();
		
		parent::foreverdelete('eventcategory',$sId);
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('value','G');

		$arrIds=explode(',',$sId);
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				$nEventcategorys=EventcategoryModel::F('eventcategory_parentid=?',$nId)->all()->getCounts();
				$oEventcategory=EventcategoryModel::F('eventcategory_id=?',$nId)->query();
				if($nEventcategorys>0){
					$this->E(Dyhb::L('活动类型%s存在子类型，你无法删除','__APPEVENT_COMMON_LANG__@Controller',null,$oEventcategory->eventcategory_name));
				}
			}
		}
	}

	protected function aForeverdelete($sId){
		$sId=G::getGpc('value','G');

		$arrIds=explode(',',$sId);
		
		// 解除关联小组数组
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				// 删除小组关联
				$oEventcategoryindexMeta=EventcategoryindexModel::M();
				$oEventcategoryindexMeta->deleteWhere(array('eventcategory_id'=>$nId));

				if($oEventcategoryindexMeta->isError()){
					$this->E($oEventcategoryindexMeta->getErrorMessage());
				}
			}
		}
	}

	public function get_parent_eventcategory($nParentEventcategoryId){
		$oEventcategory=Dyhb::instance('EventcategoryModel');

		return $oEventcategory->getParentEventcategory($nParentEventcategoryId);
	}

	public function input_change_ajax($sName=null){
		parent::input_change_ajax('eventcategory');
	}

}
