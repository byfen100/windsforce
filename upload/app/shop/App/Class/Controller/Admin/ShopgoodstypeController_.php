<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商品类型控制器($)*/

!defined('DYHB_PATH') && exit;

class ShopgoodstypeController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['shopgoodstype_name']=array('like',"%".G::getGpc('shopgoodstype_name')."%");
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('shopgoodstype',false);

		$this->display(Admin_Extend::template('shop','shopgoodstype/index'));
	}

	public function add(){
		$this->display(Admin_Extend::template('shop','shopgoodstype/add'));
	}
	
	public function insert($sModel=null,$nId=null){
		$nId=G::getGpc('value');
	
		parent::insert('shopgoodstype',$nId);
	}
	
	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		parent::edit('shopgoodstype',$nId,false);
		$this->display(Admin_Extend::template('shop','shopgoodstype/add'));
	}
	
	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');
	
		parent::update('shopgoodstype',$nId);
	}

	public function get_attributenum($nShopgoodstypeid){
		return ShopattributeModel::F('shopgoodstype_id=?',$nShopgoodstypeid)->all()->getCounts();
	}

}
