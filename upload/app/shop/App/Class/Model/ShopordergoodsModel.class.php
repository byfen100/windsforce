<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商城商品订单商品模型($)*/

!defined('DYHB_PATH') && exit;

class ShopordergoodsModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shopordergoods',
			'props'=>array(
				'shoporder_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shopordergoods_id',
			'autofill'=>array(
				array('user_id','userId','create','callback'),
			),
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}

	public function insertShopordergoods($arrShopordergoods){
		$oShopordergoods=new self();
		$oShopordergoods->changeProp($arrShopordergoods);
		$oShopordergoods->save(0);

		if($oShopordergoods->isError()){
			$this->serErrorMessage($oShopordergoods->getErrorMessage());
		}
	}

	protected function userId(){
		$nUserId=intval(G::getGpc('user_id'));
		return $nUserId>0?$nUserId:0;
	}

}
