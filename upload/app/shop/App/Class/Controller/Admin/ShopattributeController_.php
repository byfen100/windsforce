<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   商品类型属性控制器($)*/

!defined('DYHB_PATH') && exit;

/** 导入商城模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/shop/App/Class/Model');

class ShopattributeController extends InitController{

	public function filter_(&$arrMap){
		// 类型
		$nShopgoodstype=intval(G::getGpc('tid'));
		if(empty($nShopgoodstype)){
			$this->E('商品类型不能为空');
		}
		
		$oShopgoodstype=ShopgoodstypeModel::F('shopgoodstype_id=?',$nShopgoodstype)->getOne();
		if(empty($oShopgoodstype['shopgoodstype_id'])){
			$this->E('商品类型不存在');
		}
		
		$this->assign('nShopgoodstype',$nShopgoodstype);
		$this->assign('oShopgoodstype',$oShopgoodstype);
		
		$arrMap['shopgoodstype_id']=$nShopgoodstype;

		// 模糊查找
		$arrMap['shopattribute_name']=array('like',"%".G::getGpc('shopattribute_name')."%");
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('shopattribute',false);

		$this->display(Admin_Extend::template('shop','shopattribute/index'));
	}

	public function add(){
		$nShopgoodstype=intval(G::getGpc('tid','G'));
		if(empty($nShopgoodstype)){
			$this->E('商品类型不能为空');
		}
		
		$this->assign('nShopgoodstype',$nShopgoodstype);
		
		$this->display(Admin_Extend::template('shop','shopattribute/add'));
	}
	
	public function insert($sModel=null,$nId=null){
		$nId=G::getGpc('value');
	
		parent::insert('shopattribute',$nId);
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
	
		parent::edit('shopattribute',$nId,false);
		$this->display(Admin_Extend::template('shop','shopattribute/add'));
	}
	
	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');
	
		parent::update('shopattribute',$nId);
	}

}
