<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   角色分组控制器($)*/

!defined('DYHB_PATH') && exit;

class RolegroupController extends InitController{

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

		if($nId<=5){
			return true;
		}

		return false;
	}

}
