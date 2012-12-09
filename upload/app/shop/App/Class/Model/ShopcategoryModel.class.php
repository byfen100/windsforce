<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   商城商品分类模型($)*/

!defined('DYHB_PATH') && exit;

class ShopcategoryModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shopcategory',
			'props'=>array(
				'shopcategory_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'shopcategory_id',
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}
	
	public function getShopcategory(){
		return self::F()->order('shopcategory_id ASC,shopcategory_sort DESC')->all()->query();
	}
	
	public function getShopcategoryTree(){
		$arrShopcategorys=$this->getShopcategory();
	
		$oShopcategoryTree=new TreeCategory();
		foreach($arrShopcategorys as $oCategory){
			$oShopcategoryTree->setNode($oCategory->shopcategory_id,$oCategory->shopcategory_parentid,$oCategory->shopcategory_name);
		}
	
		return $oShopcategoryTree;
	}

}
