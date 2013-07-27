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
			'check'=>array(
				'eventcategory_name'=>array(
					array('require',Dyhb::L('活动类型名称不能为空','__APPEVENT_COMMON_LANG__@Model')),
					array('max_length',255,Dyhb::L('活动类型名称不能超过255个字符','__APPEVENT_COMMON_LANG__@Model')),
				),
				'eventcategory_parentid'=>array(
					array('eventcategoryParentId',Dyhb::L('活动类型不能为自己','__APPEVENT_COMMON_LANG__@Model'),'condition'=>'must','extend'=>'callback'),
				),
				'eventcategory_sort'=>array(
					array('number',Dyhb::L('序号只能是数字','__APPEVENT_COMMON_LANG__@Model'),'condition'=>'notempty','extend'=>'regex'),
				)
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

	public function safeInput(){
	}

	public function getEventcategory(){
		return self::F()->order('eventcategory_id ASC,eventcategory_sort DESC')->all()->query();
	}

	public function eventcategoryParentId(){
		$nEventcategoryId=G::getGpc('value');
		$nEventcategoryParentid=G::getGpc('eventcategory_parentid');

		if(!empty($nEventcategoryId) && !empty($nEventcategoryParentid) && $nEventcategoryId==$nEventcategoryParentid){
			return false;
		}

		return true;
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
