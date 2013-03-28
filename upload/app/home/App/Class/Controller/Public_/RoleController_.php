<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   系统角色($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class RoleController extends Controller{

	public function index(){
		$nGId=intval(G::getGpc('gid','G'));

		if($nGId){
			$oRolegroup=RolegroupModel::F('rolegroup_id=?',$nGId)->getOne();
			if(empty($oRolegroup['rolegroup_id'])){
				$nGId='';
			}
		}
		
		// 角色
		if(!empty($nGId)){
			$arrRoles=RoleModel::F('rolegroup_id=?',$nGId)->getAll();
		}else{
			$arrRoles=RoleModel::F()->getAll();
		}
		
		$this->assign('arrRoles',$arrRoles);
		$this->assign('nGId',$nGId);

		// 角色分组
		$arrRolegroups=RolegroupModel::F()->getAll();

		$this->assign('arrRolegroups',$arrRolegroups);
		
		$this->display('public+role');
	}

	public function role_title_(){
		return Dyhb::L('系统角色','Controller/Public');
	}

	public function role_keywords_(){
		return $this->role_title_();
	}

	public function role_description_(){
		return $this->role_title_();
	}

}
