<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   菜单控制器($)*/

!defined('DYHB_PATH') && exit;

class NavController extends InitController{
	
	public function index($sName=NULL,$bDisplay=true){
		$arrMap=array();

		$nLocation=intval(G::getGpc('location','G'));
		if(!in_array($nLocation,array(0,1,2))){
			$nLocation=0;
		}

		$arrMap['nav_location']=$nLocation;
		$arrNavs=NavModel::F()->where($arrMap)->order('nav_sort DESC')->getAll();
		
		$this->assign('oList',$arrNavs);
		$this->assign('nLocation',$nLocation);

		$this->display();
	}

	public function AEditObject_($oModel){
		$arrStyle=array();

		if(!empty($oModel->nav_style)){
			$arrStyle=unserialize($oModel->nav_style);
		}else{
			$arrStyle=array(0=>0,1=>0,2=>0);
		}

		$this->assign('arrStyle',$arrStyle);
	}

	public function AInsertObject_($oModel){
		$oModel->nav_type=1;
		$oModel->customIdentifier();

		$oModel->safeInput();
	}

	protected function aInsert($nId=null){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache("Nav");
	}

	protected function aUpdate($nId=null){
		$this->aInsert();
	}

	protected function aForeverdelete($sId){
		$this->aInsert();
	}

	protected function aForbid(){
		$this->aInsert();
	}

	protected function aResume(){
		$this->aInsert();
	}

	public function AUpdateObject_($oModel){
		$arrStyle=G::getGpc('style');

		if(!isset($arrStyle[0])){
			$arrStyle[0]=0;
		}

		if(!isset($arrStyle[1])){
			$arrStyle[1]=0;
		}

		if(!isset($arrStyle[2])){
			$arrStyle[2]=0;
		}

		$oModel->nav_style=serialize($arrStyle);

		$oModel->safeInput();
	}

	public function bAdd_(){
		$this->bEdit_();
	}

	public function bEdit_(){
		$arrNavs=$this->nar_parent();

		$this->assign('arrNavs',$arrNavs);

		$nLocation=intval(G::getGpc('location','G'));
		if(!in_array($nLocation,array(0,1,2))){
			$nLocation=0;
		}
		$this->assign('nLocation',$nLocation);
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			// 系统衔接不能够被删除
			$oNavModel=NavModel::F('nav_id=?',$nId)->getOne();
			if(!$oNavModel['nav_type']){
				$this->E(Dyhb::L('内置的衔接不能够被删除','Controller/Nav'));
			}
		}
	}

	public function nar_parent(){
		$arrNavs=NavModel::F()->where(array('nav_parentid'=>0,'nav_location'=>0))->order('nav_sort DESC')->getAll();
		
		return $arrNavs;
	}
	
}
