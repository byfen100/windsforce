<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   站点帮组控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入主页模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/home/App/Class/Model');

class HomehelpController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['homehelp_title']=array('like','%'.G::getGpc('homehelp_title').'%');
	}

	public function bIndex_(){
		$this->bAdd_();
	}
	
	public function index($sModel=null,$bDisplay=true){
		$this->bIndex_();
		
		parent::index('homehelp',false);
		
		$this->display(Admin_Extend::template('home','homehelp/index'));
	}

	public function bEdit_(){
		$this->bAdd_();
	}

	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		$this->bEdit_();

		parent::edit('homehelp',$nId,false);
		$this->display(Admin_Extend::template('home','homehelp/add'));
	}
	
	public function bAdd_(){
		$oHomehelpcategory=new HomehelpcategoryModel();

		$arrHomehelpcategorys=$oHomehelpcategory->getHomehelpcategory();
		$this->assign('arrHomehelpcategorys',$arrHomehelpcategorys);
	}
	
	public function add(){
		$this->bAdd_();

		$this->display(Admin_Extend::template('home','homehelp/add'));
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}
	
	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::update('homehelp',$nId);
	}

	protected function aUpdate($nId=null){
		$this->aForbid();
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}
	
	public function insert($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::insert('homehelp',$nId);
	}

	protected function aInsert($nId=null){
		$this->aForbid();
	}
	
	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');

		$this->bForeverdelete_();

		parent::foreverdelete('homehelp',$sId);
	}

	public function forbid($sModel=null,$sId=null,$bApp=false){
		$nId=intval(G::getGpc('value','G'));

		parent::forbid('homehelp',$nId,true);
	}

	protected function aForbid(){
		$nId=intval(G::getGpc('value','G'));

		$oHomehelp=HomehelpModel::F('homehelp_id=?',$nId)->getOne();
		if(!empty($oHomehelp['homehelpcategory_id'])){
			$this->homehelpcategory_count($oHomehelp['homehelpcategory_id']);
		}
	}

	public function resume($sModel=null,$sId=null,$bApp=false){
		$nId=intval(G::getGpc('value','G'));

		parent::resume('homehelp',$nId,true);
	}

	protected function aResume(){
		$this->aForbid();
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('value','G');

		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			if($this->is_system_homehelp($nId)){
				$this->E(Dyhb::L('系统站点帮助无法删除','__APP_ADMIN_LANG__@Controller/Homehelp'));
			}
		}
	}

	public function change_homehelpcategory(){
		$sId=trim(G::getGpc('value','G'));
		$nHomehelpcategoryId=intval(G::getGpc('homehelpcategory_id','G'));
		Dyhb::L('你要移动的站点帮助分类不存在','__APP_ADMIN_LANG__@Controller/Homehelp');Dyhb::L('移动站点帮助分类成功','__APP_ADMIN_LANG__@Controller/Homehelp');
		if(!empty($sId)){
			if($nHomehelpcategoryId){
				// 判断帮组分类是否存在
				$oHomehelpcategory=HomehelpcategoryModel::F('homehelpcategory_id=?',$nHomehelpcategoryId)->getOne();
				if(empty($oHomehelpcategory['homehelpcategory_id'])){
					$this->E(Dyhb::L('你要移动的站点帮助分类不存在','__APP_ADMIN_LANG__@Controller/Homehelp'));
				}
			}
			
			$arrIds=explode(',', $sId);
			foreach($arrIds as $nId){
				$oHomehelp=HomehelpModel::F('homehelp_id=?',$nId)->getOne();
				$oHomehelp->homehelpcategory_id=$nHomehelpcategoryId;
				$oHomehelp->save(0,'update');
				
				if($oHomehelp->isError()){
					$this->E($oHomehelp->getErrorMessage());
				}

				$this->homehelpcategory_count($nId,$nHomehelpcategoryId);
			}

			$this->S(Dyhb::L('移动站点帮助分类成功','__APP_ADMIN_LANG__@Controller/Homehelp'));
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function homehelpcategory_count($nOld,$nNow=null){
		$nOld=intval($nOld);
		$nNow=intval($nNow);
		
		$oHomehelpcategory=new HomehelpcategoryModel();
		$oHomehelpcategory->homehelpcategoryCount($nOld);
		
		if(!empty($nNow)){
			$oHomehelpcategory->homehelpcategoryCount($nNow);
		}
	}

	public function is_system_homehelp($nId){
		if($nId<=8){
			return true;
		}

		return false;
	}
	
}
