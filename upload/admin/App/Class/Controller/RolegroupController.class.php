<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   角色分组控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class RolegroupController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller/Common'));
		}
	}
	
	public function filter_(&$arrMap){
		$arrMap['rolegroup_name']=array('like',"%".G::getGpc('rolegroup_name')."%");
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function bEdit_(){
		$nId=intval(G::getGpc('id','G'));

		if($this->is_system_rolegroup($nId)){
			$this->E(Dyhb::L('系统角色分类无法编辑','Controller/Rolegroup'));
		}
	}

	public function bForbid_(){
		$nId=intval(G::getGpc('id','G'));

		if($this->is_system_rolegroup($nId)){
			$this->E(Dyhb::L('系统角色分组无法禁用','Controller/Rolegroup'));
		}
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			if($this->is_system_rolegroup($nId)){
				$this->E(Dyhb::L('系统角色分组无法删除','Controller/Rolegroup'));
			}
		}
	}

	public function is_system_rolegroup($nId){
		$nId=intval($nId);

		$oRolegroup=RolegroupModel::F('rolegroup_id=?',$nId)->getOne();
		if(empty($oRolegroup['rolegroup_id'])){
			return false;
		}

		if($oRolegroup['rolegroup_issystem']==1){
			return true;
		}

		return false;
	}

}
