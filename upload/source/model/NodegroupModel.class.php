<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   节点分组模型($)*/

!defined('DYHB_PATH') && exit;

class NodegroupModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'nodegroup',
			'props'=>array(
				'nodegroup_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'nodegroup_id',
			'check'=>array(
				'nodegroup_name'=>array(
					array('require',Dyhb::L('组名不能为空','__COMMON_LANG__@Model/Nodegroup')),
					array('english',Dyhb::L('组名只能为英文字符','__COMMON_LANG__@Model/Nodegroup')),
					array('max_length',50,Dyhb::L('组名最大长度为50个字符','__COMMON_LANG__@Model/Nodegroup')),
					array('nodegroupName',Dyhb::L('组名已经存在','__COMMON_LANG__@Model/Nodegroup'),'condition'=>'must','extend'=>'callback'),
				),
				'nodegroup_title'=>array(
					array('require',Dyhb::L('组显示名不能为空','__COMMON_LANG__@Model/Nodegroup')),
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

	public function nodegroupName(){
		$nId=G::getGpc('id','P');

		$sNodegroupName=G::getGpc('nodegroup_name','P');
		$sNodegroupInfo='';
		if($nId){
			$arrNodegroup=self::F('nodegroup_id=?',$nId)->asArray()->getOne();
			$sNodegroupInfo=trim($arrNodegroup['nodegroup_name']);
		}

		if($sNodegroupName !=$sNodegroupInfo){
			$arrResult=self::F()->getBynodegroup_name($sNodegroupName)->toArray();
			if(!empty($arrResult['nodegroup_id'])){
				return false;
			}else{
				return true;
			}
		}

		return true;
	}

	public function safeInput(){
		if(isset($_POST['nodegroup_name'])){
			$_POST['nodegroup_name']=G::html($_POST['nodegroup_name']);
		}

		$_POST['nodegroup_title']=G::html($_POST['nodegroup_title']);
	}

}
