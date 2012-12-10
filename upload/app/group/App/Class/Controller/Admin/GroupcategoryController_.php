<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   群组分类控制器($)*/

!defined('DYHB_PATH') && exit;

/** 导入群组模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/group/App/Class/Model');

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
		$oGroupcategory=Dyhb::instance('GroupcategoryModel');
		$arrGroupcategorys=$oGroupcategory->getGroupcategory();

		$oGroupcategoryTree=new TreeCategory();
		foreach($arrGroupcategorys as $oCategory){
			$oGroupcategoryTree->setNode($oCategory->groupcategory_id,$oCategory->groupcategory_parentid,$oCategory->groupcategory_name);
		}

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
		foreach($arrIds as $nId){
			$nGroupcategorys=GroupcategoryModel::F('groupcategory_parentid=?',$nId)->all()->getCounts();
			$oGroupcategory=GroupcategoryModel::F('groupcategory_id=?',$nId)->query();
			if($nGroupcategorys>0){
				$this->E(Dyhb::L('群组分类%s存在子分类，你无法删除','__APP_ADMIN_LANG__@Controller/Groupcategory',null,$oGroupcategory->groupcategory_name));
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
