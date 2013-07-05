<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动类型模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class EventcategoryModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'eventcategory',
			'props'=>array(
				'eventcategory_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'eventcategory_id',
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}

	public function safeInput(){
	}

	public function getEventcategory(){
		return self::F()->order('eventcategory_id ASC,eventcategory_sort DESC')->all()->query();
	}

	public function getParentEventcategory($nParentEventcategoryId){
		if($nParentEventcategoryId==0){
			return Dyhb::L('顶级分类','__APPEVENT_COMMON_LANG__@Model');
		}else{
			$oEventcategory=self::F('eventcategory_id=?',$nParentEventcategoryId)->query();
			if(!empty($oEventcategory->eventcategory_id)){
				return $oEventcategory->eventcategory_name;
			}else{
				return Dyhb::L('活动父级类型已经损坏，你可以编辑分类进行修复','__APPEVENT_COMMON_LANG__@Model');
			}
		}
	}

	public function getEventcategoryTree(){
		$arrEventcategorys=$this->getEventcategory();
		
		$oEventcategoryTree=new TreeCategory();
		foreach($arrEventcategorys as $oCategory){
			$oEventcategoryTree->setNode($oCategory->eventcategory_id,$oCategory->eventcategory_parentid,$oCategory->eventcategory_name);
		}
		
		return $oEventcategoryTree;
	}

}
