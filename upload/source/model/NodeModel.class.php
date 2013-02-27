<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   节点模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class NodeModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'node',
			'props'=>array(
				'node_id'=>array('readonly'=>true),
				'nodegroup'=>array(Db::BELONGS_TO=>'NodegroupModel','target_key'=>'nodegroup_id','skip_empty'=>true),
			),
			'attr_protected'=>'node_id',
			'check'=>array(
				'node_name'=>array(
					array('require',Dyhb::L('节点名不能为空','__COMMON_LANG__@Model/Node')),
					array('max_length',50,Dyhb::L('节点名最大长度为50个字符','__COMMON_LANG__@Model/Node')),
					array('nodeName',Dyhb::L('节点名已经存在','__COMMON_LANG__@Model/Node'),'condition'=>'must','extend'=>'callback'),
				),
				'node_title'=>array(
					array('require',Dyhb::L('显示名不能为空','__COMMON_LANG__@Model/Node')),
					array('max_length',50,Dyhb::L('显示名最大长度为50个字符','__COMMON_LANG__@Model/Node')),
				),
				'node_parentid'=>array(
					array('nodeParentId',Dyhb::L('节点不能为自己','__COMMON_LANG__@Model/Node'),'condition'=>'must','extend'=>'callback'),
				),
				'node_sort'=>array(
					array('number',Dyhb::L('序号只能是数字','__COMMON_LANG__@Model/Common'),'condition'=>'notempty','extend'=>'regex'),
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

	public function nodeParentId(){
		$nNodeId=G::getGpc('id');

		$nNodeParentid=G::getGpc('node_parentid');
		if(($nNodeId==$nNodeParentid)
				and !empty($nNodeId)
				and !empty($nNodeParentid)){
			return false;
		}

		return true;
	}

	public function nodeName(){
		$nId=G::getGpc('id','P');

		$sNodeName=G::getGpc('node_name','P');
		$sNodeInfo='';
		if($nId){
			$arrNode=self::F('node_id=?',$nId)->asArray()->getOne();
			$sNodeInfo=trim($arrNode['node_name']);
		}

		if($sNodeName!=$sNodeInfo){
			$arrResult=self::F()->getBynode_name($sNodeName)->toArray();
			if(!empty($arrResult['node_id'])){
				return false;
			}else{
				return true;
			}
		}

		return true;
	}

	public function safeInput(){
		$_POST['node_name']=G::html($_POST['node_name']);
		$_POST['node_title']=G::html($_POST['node_title']);
		$_POST['node_remark']=G::html($_POST['node_remark']);
	}

}
