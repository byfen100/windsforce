<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   角色控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class RoleController extends InitController{

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller/Common'));
		}
	}
	
	public function filter_(&$arrMap){
		$arrMap['role_name']=array('like',"%".G::getGpc('role_name')."%");
		
		$nRolegroupId=G::getGpc('rolegroup_id');
		if($nRolegroupId!==null){
			$arrMap['rolegroup_id']=$nRolegroupId;
		}
	}

	public function bIndex_(){
		$sSort=trim(G::getGpc('sort_','G'));

		$this->getRolegroup();
		
		if(!$sSort){
			$this->U('role/index?sort_=asc');
		}
	}
	
	public function bEdit_(){
		$nId=intval(G::getGpc('id','G'));

		if($this->is_system_role($nId)){
			$this->E(Dyhb::L('系统角色无法编辑','Controller/Role'));
		}

		$this->getRolegroup();
	}

	public function bAdd_(){
		$this->getRolegroup();
	}

	public function bForbid_(){
		$nId=intval(G::getGpc('id','G'));

		if($this->is_system_role($nId)){
			$this->E(Dyhb::L('系统角色无法禁用','Controller/Role'));
		}
	}
	
	public function getRolegroup(){
		$arrRolegroup=array_merge(array(array('rolegroup_id'=>0,'rolegroup_title'=>Dyhb::L('未分组','Controller/Role'))),
				RolegroupModel::F()->setColumns('rolegroup_id,rolegroup_title')->asArray()->all()->query()
		);
		$this->assign('arrRolegroup',$arrRolegroup);
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			if($this->is_system_role($nId)){
				$this->E(Dyhb::L('系统角色无法删除','Controller/Role'));
			}
			
			$nRoles=RoleModel::F('role_parentid=?',$nId)->all()->getCounts();
			$oRole=RoleModel::F('role_id=?',$nId)->query();
			if($nRoles>0){
				$this->E(Dyhb::L('用户角色%s存在子分类，你无法删除','Controller/Role',null,$oRole->role_name));
			}
		}
	}

	public function select(){
		$arrMap=array();
		$nParentid=G::getGpc('role_parentid');

		if($nParentid!=''){
			$arrMap['role_parentid']=$nParentid;
		}

		$arrList=RoleModel::F($arrMap)->setColumns('role_id,role_name')->all()->asArray()->query();
		$this->assign('arrList',$arrList);

		$this->display();
	}

	public function bSet_app_(){
		$nGroupId=G::getGpc('group_id');
		if(empty($nGroupId)){
			$this->E(Dyhb::L('你没有选择分组','Controller/Role'));
		}
	}

	public function set_app(){
		$nId=G::getGpc('groupAppId');

		$nGroupId=G::getGpc('group_id');
		$oRoleModel=RoleModel::F()->query();
		$oRoleModel->delGroupApp($nGroupId);
		$bResult=$oRoleModel->setGroupApps($nGroupId,$nId);
		if($bResult===false){
			$this->E(Dyhb::L('项目授权失败','Controller/Role'));
		}else{
			$this->S(Dyhb::L('项目授权成功','Controller/Role'));
		}
	}

	public function app(){
		$arrAppList=array();
		$arrList=NodeModel::F('node_level=?',1)->setColumns('node_id,node_title')->asArray()->all()->query();
		if(is_array($arrList)){
			foreach($arrList as $arrVo){
				if($arrVo['node_id']!=1){
					$arrAppList[$arrVo['node_id']]=$arrVo['node_title'];
				}
			}
		}

		$arrGroupList=array();
		$arrList=RoleModel::F()->setColumns('role_id,role_name')->asArray()->all()->query();
		if($arrList){
			foreach($arrList as $arrVo){
				$arrGroupList[$arrVo['role_id']]=$arrVo['role_name'];
			}
		}
		$this->assign("arrGroupList",$arrGroupList);

		$nGroupId=G::getGpc('group_id');
		if($nGroupId===null){
			$nGroupId=0;
		}
		$this->assign('nGroupId',$nGroupId);

		$arrGroupAppList=array();
		$this->assign("nSelectGroupId",$nGroupId);
		if(!empty($nGroupId)){
			$arrList=RoleModel::F()->query()->getGroupAppList($nGroupId);
			if(is_array($arrList)){
				foreach($arrList as $arrVo){
					$arrGroupAppList[]=	$arrVo['node_id'];
				}
			}
		}
		$this->assign('arrGroupAppList',$arrGroupAppList);
		$this->assign('arrAppList',$arrAppList);

		$this->display();
	}

	public function module(){
		$nGroupId=G::getGpc('group_id');
		$nAppId=G::getGpc('app_id');

		if($nGroupId===null){
			$nGroupId=0;
		}
		$this->assign('nGroupId',$nGroupId);

		$arrGroupList=array();
		$arrList=RoleModel::F()->setColumns('role_id,role_name')->all()->asArray()->query();
		if(is_array($arrList)){
			foreach($arrList as $arrVo){
				$arrGroupList[$arrVo['role_id']]=$arrVo['role_name'];
			}
		}
		$this->assign("arrGroupList",$arrGroupList);

		$arrAppList=array();
		$this->assign("nSelectGroupId",$nGroupId);
		if(!empty($nGroupId)){
			$arrList=RoleModel::F()->query()->getGroupAppList($nGroupId);
			if(is_array($arrList)){
				foreach($arrList as $arrVo){
					$arrAppList[$arrVo['node_id']]=	$arrVo['node_title'];
				}
			}
		}
		$this->assign("arrAppList",$arrAppList);

		$arrModuleList=array();
		$this->assign("nSelectAppId",$nAppId);
		if(!empty($nAppId)){
			$arrWhere['node_level']=2;
			$arrWhere['node_parentid']=$nAppId;
			$arrNodelist=NodeModel::F()->setColumns('node_id,node_title')->where($arrWhere)->asArray()->all()->query();
			if(is_array($arrNodelist)){
				foreach($arrNodelist as $arrVo){
					$arrModuleList[$arrVo['node_id']]=$arrVo['node_title'];
				}
			}
		}

		$arrGroupModuleList=array();
		if(!empty($nGroupId)&& !empty($nAppId)){
			$arrGrouplist=RoleModel::F()->query()->getGroupModuleList($nGroupId,$nAppId);
			if(is_array($arrGrouplist)){
				foreach($arrGrouplist as $arrVo){
					$arrGroupModuleList[]=$arrVo['node_id'];
				}
			}
		}
		$this->assign('arrGroupModuleList',$arrGroupModuleList);
		$this->assign('arrModuleList',$arrModuleList);

		$this->display();
	}

	public function bSet_module_(){
		$nGroupId=G::getGpc('group_id');
		$nAppId=G::getGpc('appId');

		if(empty($nGroupId)){
			$this->E(Dyhb::L('你没有选择分组','Controller/Role'));
		}

		if(empty($nAppId)){
			$this->E(Dyhb::L('你没有选择APP','Controller/Role'));
		}
	}

	public function set_module(){
		$nId=G::getGpc('groupModuleId');
		$nGroupId=G::getGpc('group_id');
		$nAppId=G::getGpc('appId');

		RoleModel::F()->query()->delGroupModule($nGroupId,$nAppId);
		$bResult=RoleModel::F()->query()->setGroupModules($nGroupId,$nId);
		if($bResult===false){
			$this->E(Dyhb::L('模块授权失败','Controller/Role'));
		}else{
			$this->S(Dyhb::L('模块授权成功','Controller/Role'));
		}
	}

	public function action(){
		$nGroupId=G::getGpc('group_id','G');
		$nAppId=G::getGpc('app_id','G');
		$nModuleId=G::getGpc('module_id','G');

		if($nGroupId===null){
			$nGroupId=0;
		}
		$this->assign('nGroupId',$nGroupId);

		if($nAppId===null){
			$nAppId=0;
		}
		$this->assign('nAppId',$nAppId);

		$arrGrouplist=RoleModel::F()->setColumns('role_id,role_name')->asArray()->all()->query();
		if(is_array($arrGrouplist)){
			foreach($arrGrouplist as $arrVo){
				$arrGroupList[$arrVo['role_id']]=$arrVo['role_name'];
			}
		}
		$this->assign("arrGroupList",$arrGroupList);
		$this->assign("nSelectGroupId",$nGroupId);

		$arrAppList=array();
		if(!empty($nGroupId)){
			$arrList=RoleModel::F()->query()->getGroupAppList($nGroupId);
			if($arrList){
				foreach($arrList as $arrVo){
					$arrAppList[$arrVo['node_id']]=	$arrVo['node_title'];
				}
			}
		}
		$this->assign("arrAppList",$arrAppList);
		$this->assign("nSelectAppId",$nAppId);

		$arrModuleList=array();
		if(!empty($nAppId)){
			$arrList=RoleModel::F()->query()->getGroupModuleList($nGroupId,$nAppId);
			if(is_array($arrList)){
				foreach($arrList as $arrVo){
					$arrModuleList[$arrVo['node_id']]=$arrVo['node_title'];
				}
			}
		}
		$this->assign("arrModuleList",$arrModuleList);
		$this->assign("nSelectModuleId",$nModuleId);

		$arrActionList=array();
		if(!empty($nModuleId)){
			$arrMap['node_level']=3;
			$arrMap['node_parentid']=$nModuleId;
			$arrList=NodeModel::F()->setColumns('node_id,node_title')->where($arrMap)->asArray()->all()->query();
			if($arrList){
				foreach($arrList as $arrVo){
					$arrActionList[$arrVo['node_id']]=$arrVo['node_title'];
				}
			}
		}
		$this->assign('arrActionList',$arrActionList);

		$arrGroupActionList=array();
		if(!empty($nModuleId) && !empty($nGroupId)){
			$arrGroupAction=RoleModel::F()->query()->getGroupActionList($nGroupId,$nModuleId);
			if($arrGroupAction){
				foreach($arrGroupAction as $arrVo){
					$arrGroupActionList[]=$arrVo['node_id'];
				}
			}
		}
		$this->assign('arrGroupActionList',$arrGroupActionList);

		$this->display();
	}

	public function bSet_action_(){
		$nGroupId=G::getGpc('group_id','P');
		$nAppId=G::getGpc('appId','P');

		if(empty($nGroupId)){
			$this->E(Dyhb::L('你没有选择分组','Controller/Role'));
		}

		if(empty($nAppId)){
			$this->E(Dyhb::L('你没有选择APP','Controller/Role'));
		}
	}

	public function set_action(){
		$nId=G::getGpc('groupActionId','P');
		$nModuleId=G::getGpc('moduleId','P');
		$nGroupId=G::getGpc('group_id','P');

		RoleModel::F()->query()->delGroupAction($nGroupId,$nModuleId);
		$bResult=RoleModel::F()->query()->setGroupActions($nGroupId,$nId);
		if($bResult===false){
			$this->E(Dyhb::L('操作授权失败','Controller/Role'));
		}else{
			$this->S(Dyhb::L('操作授权成功','Controller/Role'));
		}
	}

	public function user(){
		$nGroupId=G::getGpc('id');

		if($nGroupId==4){
			$this->E(Dyhb::L('游客用户ID为-1，无法进行授权','Controller/Role'));
		}
		
		$arrWhere=array();
		$arrWhere['user_name']=array('like','%'.G::getGpc('user_name').'%');

		$nTotalRecord=UserModel::F()->where($arrWhere)->all()->getCounts('user_id');

		$nEverynum=$GLOBALS['_option_']['admin_list_num'];
		$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));
		$sPageNavbar=$oPage->P();
		$this->assign('sPageNavbar',$sPageNavbar);

		$arrList=UserModel::F()->setColumns('user_id,user_name,user_nikename')->order('user_id DESC')->asArray()->where($arrWhere)->all()->limit($oPage->returnPageStart(),$nEverynum)->query();
		
		$arrUserList=array();
		foreach($arrList as $arrVo){
			$arrUserList[$arrVo['user_id']]=$arrVo['user_name'].' '.$arrVo['user_nikename'];
		}

		$arrList=RoleModel::F()->setColumns('role_id,role_name')->asArray()->all()->query();
		if(is_array($arrList)){
			foreach($arrList as $arrVo){
				$arrGroupList[$arrVo['role_id']]=$arrVo['role_name'];
			}
		}
		$this->assign("arrGroupList",$arrGroupList);
		$this->assign('nId',$nGroupId);

		$arrGroupUserList=array();
		$this->assign("nSelectGroupId",$nGroupId);

		$arrGroupUserList=array();
		if(!empty($nGroupId)){
			$arrList=RoleModel::F()->query()->getGroupUserList($nGroupId);
			if(is_array($arrList)){
				foreach($arrList as $arrVo){
					$arrGroupUserList[]=$arrVo['user_id'];
				}
			}
		}
		$this->assign('arrGroupUserList',$arrGroupUserList);
		$this->assign('arrUserList',$arrUserList);

		$this->display();
	}

	public function bSet_user_(){
		$nGroupId=G::getGpc('group_id','P');
		if(empty($nGroupId)){
			$this->E(Dyhb::L('授权失败','Controller/Role'));
		}
	}

	public function set_user(){
		$arrId=G::getGpc('groupUserId','P');
		$nGroupId=G::getGpc('group_id','P');
		$arrThispageuser=G::getGpc('thispageuser','P');

		RoleModel::F()->query()->delGroupUser($nGroupId,$arrThispageuser);
		$bResult=RoleModel::F()->query()->setGroupUsers($nGroupId,$arrId);
		if($bResult===false){
			$this->E(Dyhb::L('授权失败','Controller/Role'));
		}else{
			$this->S(Dyhb::L('授权成功','Controller/Role'));
		}
	}

	public function get_parent_role($nParentRoleId){
		$oRole=Dyhb::instance('RoleModel');

		return $oRole->getParentRole($nParentRoleId);
	}
	
	public function change_rolegroup(){
		$sId=trim(G::getGpc('id','G'));
		$nRolegroupId=intval(G::getGpc('rolegroup_id','G'));
		
		if(!empty($sId)){
			if($nRolegroupId){
				// 判断角色分组是否存在
				$oRolegroup=RolegroupModel::F('rolegroup_id=?',$nRolegroupId)->getOne();
				if(empty($oRolegroup['rolegroup_id'])){
					$this->E(Dyhb::L('你要移动的角色分组不存在','Controller/Role'));
				}
			}
			
			$arrIds=explode(',', $sId);
			foreach($arrIds as $nId){
				if($this->is_system_role($nId)){
					$this->E(Dyhb::L('系统角色无法移动','Controller/Role'));
				}

				$oRole=RoleModel::F('role_id=?',$nId)->getOne();
				$oRole->rolegroup_id=$nRolegroupId;
				$oRole->save(0,'update');
				
				if($oRole->isError()){
					$this->E($oRole->getErrorMessage());
				}
			}

			$this->S(Dyhb::L('移动角色分组成功','Controller/Role'));
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function is_system_role($nId){
		$nId=intval($nId);

		$oRole=RoleModel::F('role_id=?',$nId)->getOne();
		if(empty($oRole['role_id'])){
			return false;
		}

		if($oRole['role_issystem']==1){
			return true;
		}

		return false;
	}

}
