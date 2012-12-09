<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   站点帮组分类控制器($)*/

!defined('DYHB_PATH') && exit;

/** 导入主页模型 */
Dyhb::import(NEEDFORBUG_PATH.'/app/home/App/Class/Model');

class HomehelpcategoryController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['homehelpcategory_name']=array('like','%'.G::getGpc('homehelpcategory_name').'%');
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('homehelpcategory',false);

		$this->display(Admin_Extend::template('home','homehelpcategory/index'));
	}

	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		parent::edit('homehelpcategory',$nId,false);
		$this->display(Admin_Extend::template('home','homehelpcategory/add'));
	}
	
	
	public function add(){
		$this->display(Admin_Extend::template('home','homehelpcategory/add'));
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}
	
	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::update('homehelpcategory',$nId);
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}
	
	public function insert($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::insert('homehelpcategory',$nId);
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('value','G');

		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			if($this->is_system_homehelpcategory($nId)){
				$this->E(Dyhb::L('系统站点帮助分类无法删除','__APP_ADMIN_LANG__@Controller/Homehelpcategory'));
			}

			$nHomehelps=HomehelpModel::F('homehelpcategory_id=?',$nId)->all()->getCounts();
			$oHomehelpcategory=HomehelpcategoryModel::F('homehelpcategory_id=?',$nId)->query();
			if($nHomehelps>0){
				$this->E(Dyhb::L('站点帮助分类%s存在帮助内容，你无法删除','__APP_ADMIN_LANG__@Controller/Homehelpcategory',null,$oHomehelpcategory->homehelpcategory_name));
			}
		}
	}
	
	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');

		$this->bForeverdelete_();

		parent::foreverdelete('homehelpcategory',$sId);
	}

	public function input_change_ajax($sName=null){
		parent::input_change_ajax('homehelpcategory');
	}

	public function is_system_homehelpcategory($nId){
		if($nId<=3){
			return true;
		}

		return false;
	}
	
}
