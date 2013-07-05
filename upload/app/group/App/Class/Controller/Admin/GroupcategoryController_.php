<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组分类控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GroupcategoryController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['groupcategory_name']=array('like','%'.G::getGpc('groupcategory_name').'%');
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('groupcategory',false);

		$this->display(Admin_Extend::template('group','groupcategory/index'));
	}
	
	public function add(){
		$this->bAdd_();
		
		$this->display(Admin_Extend::template('group','groupcategory/add'));
	}
	
	public function bAdd_(){
		$oGroupcategoryTree=Dyhb::instance('GroupcategoryModel')->getGroupcategoryTree();

		$this->assign('oGroupcategoryTree',$oGroupcategoryTree);
	}

	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		$this->bEdit_();
		
		parent::edit('groupcategory',$nId,false);
		$this->display(Admin_Extend::template('group','groupcategory/add'));
	}

	public function bEdit_(){
		$this->bAdd_();
	}

	public function insert($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::insert('groupcategory',$nId);
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}

	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::update('groupcategory',$nId);
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');

		$this->bForeverdelete_();
		
		parent::foreverdelete('groupcategory',$sId);
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('value','G');

		$arrIds=explode(',',$sId);
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				$nGroupcategorys=GroupcategoryModel::F('groupcategory_parentid=?',$nId)->all()->getCounts();
				$oGroupcategory=GroupcategoryModel::F('groupcategory_id=?',$nId)->query();
				if($nGroupcategorys>0){
					$this->E(Dyhb::L('群组分类%s存在子分类，你无法删除','__APPGROUP_COMMON_LANG__@Controller',null,$oGroupcategory->groupcategory_name));
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
				$oGroupcategoryindexMeta=GroupcategoryindexModel::M();
				$oGroupcategoryindexMeta->deleteWhere(array('groupcategory_id'=>$nId));

				if($oGroupcategoryindexMeta->isError()){
					$this->E($oGroupcategoryindexMeta->getErrorMessage());
				}
			}
		}
	}

	public function get_parent_groupcategory($nParentGroupcategoryId){
		$oGroupcategory=Dyhb::instance('GroupcategoryModel');

		return $oGroupcategory->getParentGroupcategory($nParentGroupcategoryId);
	}

	public function input_change_ajax($sName=null){
		parent::input_change_ajax('groupcategory');
	}

}
