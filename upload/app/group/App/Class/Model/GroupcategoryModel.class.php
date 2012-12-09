<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   群组分类模型($)*/

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
					array('require',Dyhb::L('群组分类不能为空','__APP_ADMIN_LANG__@Model/Groupcategory')),
					array('max_length',32,Dyhb::L('群组分类不能超过32个字符','__APP_ADMIN_LANG__@Model/Groupcategory'))
				),
				'groupcategory_parentid'=>array(
					array('groupcategoryParentId',Dyhb::L('群组分类不能为自己','__APP_ADMIN_LANG__@Model/Groupcategory'),'condition'=>'must','extend'=>'callback'),
				),
				'groupcategory_sort'=>array(
					array('number',Dyhb::L('序号只能是数字','__APP_ADMIN_LANG__@Model/Groupcategory'),'condition'=>'notempty','extend'=>'regex'),
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
		$nGroupcategoryId=G::getGpc('id');
		$nGroupcategoryParentid=G::getGpc('groupcategory_parentid');
		if(($nGroupcategoryId==$nGroupcategoryParentid)
				and !empty($nGroupcategoryId)
				and !empty($nGroupcategoryParentid)){
			return false;
		}

		return true;
	}

	public function getGroupcategory(){
		return self::F()->order('groupcategory_id ASC,groupcategory_sort DESC')->all()->query();
	}

	public function getParentGroupcategory($nParentGroupcategoryId){
		if($nParentGroupcategoryId==0){
			return Dyhb::L('顶级分类','__APP_ADMIN_LANG__@Model/Groupcategory');
		}else{
			$oGroupcategory=self::F('groupcategory_id=?',$nParentGroupcategoryId)->query();
			if(!empty($oGroupcategory->groupcategory_id)){
				return $oGroupcategory->groupcategory_name;
			}else{
				return Dyhb::L('群组父级分类已经损坏，你可以编辑分类进行修复','__APP_ADMIN_LANG__@Model/Groupcategory');
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
