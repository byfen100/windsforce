<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   站点信息控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入主页模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/home/App/Class/Model');

class HomesiteController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['homesite_name']=array('like','%'.G::getGpc('homesite_name').'%');
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('homesite',false);

		$this->display(Admin_Extend::template('home','homesite/index'));
	}

	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		parent::edit('homesite',$nId,false);
		$this->display(Admin_Extend::template('home','homesite/add'));
	}
	
	public function add(){
		$this->display(Admin_Extend::template('home','homesite/add'));
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}
	
	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::update('homesite',$nId);
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}
	
	public function insert($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::insert('homesite',$nId);
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('value','G');

		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			if($this->is_system_homesite($nId)){
				$this->E(Dyhb::L('系统站点信息无法删除','__APP_ADMIN_LANG__@Controller/Homesite'));
			}
		}
	}
	
	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');
		
		$this->bForeverdelete_();
		
		parent::foreverdelete('homesite',$sId);
	}
	
	public function is_system_homesite($nId){
		if($nId<=4){
			return true;
		}
	
		return false;
	}

}
