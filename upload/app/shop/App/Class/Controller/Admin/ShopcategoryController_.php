<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商品分类控制器($)*/

!defined('DYHB_PATH') && exit;

/** 导入商城模型 */
Dyhb::import(NEEDFORBUG_PATH.'/app/shop/App/Class/Model');

class ShopcategoryController extends InitController{

	public function filter_(&$arrMap){
		// 取得分类父亲ID
		$nPid=intval(G::getGpc('pid'));
		if(empty($nPid)){
			$nPid=0;
		}
		$arrMap['shopcategory_parentid']=$nPid;
		
		if($nPid>0){
			$oParentShopcategory=ShopcategoryModel::F('shopcategory_id=?',$nPid)->getOne();
			if(!empty($oParentShopcategory['shopcategory_id'])){
				$this->assign('oParentShopcategory',$oParentShopcategory);
			}
		}

		$this->assign('nPid',$nPid);

		// 商品分类
		$arrMap['shopcategory_name']=array('like',"%".G::getGpc('shopcategory_name')."%");
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('shopcategory',false);

		$this->display(Admin_Extend::template('shop','shopcategory/index'));
	}

	public function add(){
		$this->display(Admin_Extend::template('shop','shopcategory/add'));
	}
	
	public function insert($sModel=null,$nId=null){
		$nId=G::getGpc('value');
	
		parent::insert('shopcategory',$nId);
	}
	
	public function bAdd_(){
		$this->get_shopcategorytree_();
	}
	
	public function get_shopcategorytree_(){
		$oShopcategory=Dyhb::instance('ShopcategoryModel');
		$oShopcategoryTree=$oShopcategory->getShopcategoryTree();
		
		$this->assign('oShopcategoryTree',$oShopcategoryTree);
	}
	
	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));
	
		$this->bAdd_();
	
		parent::edit('shopcategory',$nId,false);
		$this->display(Admin_Extend::template('shop','shopcategory/add'));
	}
	
	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');
	
		parent::update('shopcategory',$nId);
	}
	
	public function tree(){
		$this->get_shopcategorytree_();
		
		$this->display(Admin_Extend::template('shop','shopcategory/tree'));
	}
	
	public function AUpdateObject_($oModel){
	}

	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');
		
		parent::foreverdelete('shopcategory',$sId);
	}

}
