<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组分类模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GroupcategoryModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'groupcategory',
			'props'=>array(
				'groupcategory_id'=>array('readonly'=>true),
				'group'=>array('many_to_many'=>'GroupModel','mid_class'=>'GroupcategoryindexModel','mid_source_key'=>'groupcategory_id','mid_target_key'=>'group_id'),
			),
			'attr_protected'=>'groupcategory_id',
			'check'=>array(
				'groupcategory_name'=>array(
					array('require',Dyhb::L('群组分类不能为空','__APPGROUP_COMMON_LANG__@Model')),
					array('max_length',32,Dyhb::L('群组分类不能超过32个字符','__APPGROUP_COMMON_LANG__@Model'))
				),
				'groupcategory_parentid'=>array(
					array('groupcategoryParentId',Dyhb::L('群组分类不能为自己','__APPGROUP_COMMON_LANG__@Model'),'condition'=>'must','extend'=>'callback'),
				),
				'groupcategory_sort'=>array(
					array('number',Dyhb::L('序号只能是数字','__APPGROUP_COMMON_LANG__@Model'),'condition'=>'notempty','extend'=>'regex'),
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

	public function groupcategoryParentId(){
		$nGroupcategoryId=G::getGpc('value');
		$nGroupcategoryParentid=G::getGpc('groupcategory_parentid');

		if(!empty($nGroupcategoryId) && !empty($nGroupcategoryParentid) && $nGroupcategoryId==$nGroupcategoryParentid){
			return false;
		}

		return true;
	}

	public function getGroupcategory(){
		return self::F()->order('groupcategory_id ASC,groupcategory_sort DESC')->all()->query();
	}

	public function getParentGroupcategory($nParentGroupcategoryId){
		if($nParentGroupcategoryId==0){
			return Dyhb::L('顶级分类','__APPGROUP_COMMON_LANG__@Model');
		}else{
			$oGroupcategory=self::F('groupcategory_id=?',$nParentGroupcategoryId)->query();
			if(!empty($oGroupcategory->groupcategory_id)){
				return $oGroupcategory->groupcategory_name;
			}else{
				return Dyhb::L('群组父级分类已经损坏，你可以编辑分类进行修复','__APPGROUP_COMMON_LANG__@Model');
			}
		}
	}
	
	public function getGroupcategoryTree(){
		$arrGroupcategorys=$this->getGroupcategory();
		
		$oGroupcategoryTree=new TreeCategory();
		foreach($arrGroupcategorys as $oCategory){
			$oGroupcategoryTree->setNode($oCategory->groupcategory_id,$oCategory->groupcategory_parentid,$oCategory->groupcategory_name);
		}

		return $oGroupcategoryTree;
	}

	public function safeInput(){
		$_POST['groupcategory_name']=G::html($_POST['groupcategory_name']);
	}

}
