<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组帖子分类模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GrouptopiccategoryModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'grouptopiccategory',
			'props'=>array(
				'grouptopiccategory_id'=>array('readonly'=>true),
				'group'=>array(Db::HAS_ONE=>'GroupModel','source_key'=>'group_id','target_key'=>'group_id'),
			),
			'attr_protected'=>'grouptopiccategory_id',
			'check'=>array(
				'grouptopiccategory_name'=>array(
					array('require',Dyhb::L('群组帖子分类不能为空','__APPGROUP_COMMON_LANG__@Model')),
					array('max_length',30,Dyhb::L('群组帖子分类不能超过30个字符','__APPGROUP_COMMON_LANG__@Model'))
				),
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
	
	public function insertGroupcategory($nGroupId){
		$this->group_id=$nGroupId;
		$this->save(0);
	}

	public function safeInput(){
		$_POST['grouptopiccategory_name']=G::html($_POST['grouptopiccategory_name']);
	}

	public function grouptopiccategoryByGroupid($nGroupid){
		return self::F('group_id=?',$nGroupid)->order('grouptopiccategory_sort DESC')->getAll();
	}

}
