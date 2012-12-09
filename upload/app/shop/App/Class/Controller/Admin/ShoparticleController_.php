<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   商城文章控制器($)*/

!defined('DYHB_PATH') && exit;

/** 导入商城模型 */
Dyhb::import(NEEDFORBUG_PATH.'/app/shop/App/Class/Model');

class ShoparticleController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['shoparticle_title']=array('like',"%".G::getGpc('shoparticle_title')."%");
	}
	
	public function index($sModel=null,$bDisplay=true){
		parent::index('shoparticle',false);
		
		$this->display(Admin_Extend::template('shop','shoparticle/index'));
	}
	
	public function add(){
		$this->get_shoparticlecategorytree_();
		$this->display(Admin_Extend::template('shop','shoparticle/add'));
	}
	
	public function get_shoparticlecategorytree_(){
		$oShoparticlecategory=Dyhb::instance('ShoparticlecategoryModel');
		$oShoparticlecategoryTree=$oShoparticlecategory->getShoparticlecategoryTree();

		$this->assign('oShoparticlecategoryTree',$oShoparticlecategoryTree);
	}
	
	public function insert($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::insert('shoparticle',$nId);
	}
	
	public function AInsertObject_($oModel){		
		$oModel->user_id=$GLOBALS['___login___']['user_id'];
		$oModel->shoparticle_username=$GLOBALS['___login___']['user_name'];
		$oModel->shoparticle_useremail=$GLOBALS['___login___']['user_email'];
	}
	
	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));
		
		$this->get_shoparticlecategorytree_();
		
		parent::edit('shoparticle',$nId,false);
		$this->display(Admin_Extend::template('shop','shoparticle/add'));
	}
	
	public function update($sModel = NULL, $nId = NULL){
		$nId=intval(G::getGpc('value'));
		
		parent::update('shoparticle',$nId);
	}
	
	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');
		
		parent::foreverdelete('shoparticle',$sId);
	}

}
