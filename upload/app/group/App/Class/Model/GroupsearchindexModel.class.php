<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   群组帖子搜索缓存模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GroupsearchindexModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'groupsearchindex',
			'props'=>array(
				'groupsearchindex_id'=>array('readonly'=>true),
			),
			'attr_protected'=>'searchindex_id',
			'autofill'=>array(
				array('user_id','getUserid','create','callback'),
				array('groupsearchindex_ip','getIp','create','callback'),
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

	public function getUserid(){
		return $GLOBALS['___login___']!==false?$GLOBALS['___login___']['user_id']:'0';
	}

	public function getIp(){
		return G::getIp();
	}

	public function deleteAll(){
		$oDb=Db::RUN();
		return $oDb->query("DELETE FROM ".$this->getTablePrefix().'groupsearchindex');
	}

}
