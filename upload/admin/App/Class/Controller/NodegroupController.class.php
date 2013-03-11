<?php
/* [WindsForce!] (C)WindsForce TEAM Since 2012.03.17.
   节点分组控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class NodegroupController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller/Common'));
		}
	}
	
	public function filter_(&$arrMap){
		$arrMap['nodegroup_name']=array('like',"%".G::getGpc('nodegroup_name')."%");
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function aInsert($nId=null){
		$this->clear_menu_cache();
	}

	public function aUpdate($nId=null){
		$this->clear_menu_cache();
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			if($this->is_system_nodegroup($nId)){
				$this->E(Dyhb::L('系统节点分组无法删除','Controller/Nodegroup'));
			}
		}
	}

	public function bEdit_(){
		$nId=intval(G::getGpc('id','G'));

		if($this->is_system_nodegroup($nId)){
			$this->E(Dyhb::L('系统节点分组无法编辑','Controller/Nodegroup'));
		}
	}

	public function aForeverdelete($sId){
		$this->clear_menu_cache();
	}

	public function afterInputChangeAjax($sName=null){
		$this->clear_menu_cache();
	}

	public function clear_menu_cache(){
		Dyhb::cookie('_access_list_','',-1);
	}

	public function sort(){
		$nSortId=G::getGpc('sort_id','G');

		if(!empty($nSortId)){
			$arrMap['nodegroup_status']=1;
			$arrMap['nodegroup_id']=array('in',$nSortId);
			$arrSortList=NodegroupModel::F()->order('nodegroup_sort ASC')->where($arrMap)->all()->query();
		}else{
			$arrSortList=NodegroupModel::F()->order('nodegroup_sort ASC')->all()->query();
		}
		$this->assign("arrSortList",$arrSortList);

		$this->display();
	}

	public function is_system_nodegroup($nId){
		$nId=intval($nId);

		$oNodegroup=NodegroupModel::F('nodegroup_id=?',$nId)->getOne();
		if(empty($oNodegroup['nodegroup_id'])){
			return false;
		}

		if($oNodegroup['nodegroup_issystem']==1){
			return true;
		}

		return false;
	}

}
