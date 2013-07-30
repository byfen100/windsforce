<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商城文章分类模型($)*/

!defined('DYHB_PATH') && exit;

class ShoparticlecategoryModel extends Model{

	static public function init__(){
		return array(
			'table_name'=>'shoparticlecategory',
			'props'=>array(
				'shoparticlecategory_id'=>array('readonly'=>true),
				'shoparticle'=>array(Db::HAS_MANY=>'ShoparticleModel','source_key'=>'shoparticlecategory_id','target_key'=>'shoparticlecategory_id'),
			),
			'attr_protected'=>'shoparticlecategory_id',
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}
	
	public function getShoparticlecategory(){
		return self::F()->order('shoparticlecategory_id ASC,shoparticlecategory_sort DESC')->all()->query();
	}
	
	public function getShoparticlecategoryTree(){
		$arrShoparticlecategorys=$this->getShoparticlecategory();
	
		$oShoparticlecategoryTree=new TreeCategory();
		foreach($arrShoparticlecategorys as $oCategory){
			$oShoparticlecategoryTree->setNode($oCategory->shoparticlecategory_id,$oCategory->shoparticlecategory_parentid,$oCategory->shoparticlecategory_name);
		}
	
		return $oShoparticlecategoryTree;
	}

}
