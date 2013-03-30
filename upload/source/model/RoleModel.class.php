<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   角色模型($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class RoleModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'role',
			'props'=>array(
				'role_id'=>array('readonly'=>true),
				'rolegroup'=>array(Db::BELONGS_TO=>'RolegroupModel','target_key'=>'rolegroup_id','skip_empty'=>true),
			),
			'attr_protected'=>'role_id',
			'check'=>array(
				'role_name'=>array(
					array('require',Dyhb::L('角色名不能为空','__COMMON_LANG__@Model/Role')),
					array('max_length',50,Dyhb::L('角色名最大长度为50个字符','__COMMON_LANG__@Model/Role')),
					array('uniqueRoleName',Dyhb::L('角色名已经存在','__COMMON_LANG__@Model/Role'),'condition'=>'must','extend'=>'callback'),
				),
				'role_parentid'=>array(
					array('uniqueRoleParentId',Dyhb::L('上级组不能为自己','__COMMON_LANG__@Model/Role'),'condition'=>'must','extend'=>'callback'),
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

	public function getParentRoleName($nRoleId){
		if($nRoleId==0){
			return;
		}

		$oRole=self::F($nRoleId)->getOne();
		if(!$oRole->isError()){
			return $oRole->role_name;
		}
	}

	public function uniqueRoleName(){
		$nId=G::getGpc('id');

		$sRoleName=trim(G::getGpc('role_name'));
		$sRoleInfo='';

		if($nId){
			$arrRole=self::F('role_id=?',$nId)->asArray()->getOne();
			$sRoleInfo=trim($arrRole['role_name']);
		}

		if($sRoleName !=$sRoleInfo){
			$arrResult=self::F()->getByrole_name($sRoleName)->toArray();
			if(!empty($arrResult['role_id'])){
				return false;
			}else{
				return true;
			}
		}

		return true;
	}

	public function uniqueRoleParentId(){
		$nRoleId=G::getGpc('id');

		$nRoleParentId=G::getGpc('role_parentid');
		if(($nRoleId==$nRoleParentId) && $nRoleId!='' && $nRoleParentId!=''){
			return false;
		}

		return true;
	}

	public function getGroupAppList($nGroupId){
		$oDb=$this->getDb();
		return $oDb->getAllRows('SELECT b.node_id,b.node_title,b.node_name FROM '.
				AccessModel::F()->query()->getTablePrefix().'access AS a,'.
				NodeModel::F()->query()->getTablePrefix().'node AS b WHERE a.node_id=b.node_id AND  b.node_parentid=0 AND a.role_id='.$nGroupId);
	}

	public function delGroupApp($nGroupId){
		$oDb=$this->getDb();

		$bResult=$oDb->query('DELETE FROM '.AccessModel::F()->query()->getTablePrefix().'access WHERE `access_level`=1 AND role_id='.$nGroupId);
		if($bResult===false){
			return false;
		}else{
			return true;
		}
	}

	public function setGroupApps($nGroupId,$arrAppIdList){
		$oDb=$this->getDb();

		if(empty($arrAppIdList)){
			return true;
		}

		$sId=implode(',',$arrAppIdList);
		$bResult=$oDb->query('INSERT INTO '.AccessModel::F()->query()->getTablePrefix().
				'access(role_id,node_id,access_parentid,access_level)SELECT a.role_id,b.node_id,b.node_parentid,b.node_level FROM '.
				RoleModel::F()->query()->getTablePrefix().'role a,'.
				NodeModel::F()->query()->getTablePrefix().
				'node b WHERE '.'a.role_id='.$nGroupId.' AND b.node_id in('.$sId.')');
		if($bResult===false){
			return false;
		}else{
			return true;
		}
	}

	public function getGroupModuleList($nGroupId,$nAppId){
		$oDb=$this->getDb();

		return $oDb->getAllRows('SELECT b.node_id,b.node_title,b.node_name FROM '.
				AccessModel::F()->query()->getTablePrefix().'access AS a ,'.
				NodeModel::F()->query()->getTablePrefix().
				'node AS b WHERE a.node_id=b.node_id AND b.node_parentid='.$nAppId.' AND a.role_id='.$nGroupId);
	}

	public function delGroupModule($nGroupId,$nAppId){
		$oDb=$this->getDb();

		$nResult=$oDb->query('DELETE FROM '.AccessModel::F()->query()->getTablePrefix().'access WHERE access_level=2 AND access_parentid='.$nAppId.' AND role_id='.$nGroupId);
		if($nResult===false){
			return false;
		}else{
			return true;
		}
	}

	public function setGroupModules($nGroupId,$arrModuleIdList){
		$oDb=$this->getDb();

		if(empty($arrModuleIdList)){
			return true;
		}

		if(is_array($arrModuleIdList)){
			$arrModuleIdList=implode(',',$arrModuleIdList);
		}
		$bResult=$oDb->query('INSERT INTO '.AccessModel::F()->query()->getTablePrefix().
				'access(role_id,node_id,access_parentid,access_level)SELECT a.role_id,b.node_id,b.node_parentid,b.node_level FROM '.
				RoleModel::F()->query()->getTablePrefix().'role a,'.NodeModel::F()->query()->getTablePrefix().
				'node b WHERE '.'a.role_id='.$nGroupId.' AND b.node_id in('.$arrModuleIdList.')');

		if($bResult===false){
			return false;
		}else{
			return true;
		}
	}

	public function getGroupActionList($nGroupId,$nModuleId){
		$oDb=$this->getDb();

		return $oDb->getAllRows('SELECT b.node_id,b.node_title,b.node_name FROM '.
			AccessModel::F()->query()->getTablePrefix().'access AS a ,'.
			NodeModel::F()->query()->getTablePrefix().'node AS b WHERE a.node_id=b.node_id AND b.node_parentid='.$nModuleId.' AND a.role_id='.$nGroupId);

	}

	public function delGroupAction($nGroupId,$nModuleId){
		$oDb=$this->getDb();

		$bResult=$oDb->query('DELETE FROM '.AccessModel::F()->query()->getTablePrefix().
				'access WHERE access_level=3 AND access_parentid='.$nModuleId.' AND role_id='.$nGroupId);
		if($bResult===false){
			return false;
		}else{
			return true;
		}
	}

	public function setGroupActions($nGroupId,$arrActionIdList){
		$oDb=$this->getDb();

		if(empty($arrActionIdList)){
			return true;
		}

		if(is_array($arrActionIdList)){
			$arrActionIdList=implode(',',$arrActionIdList);
		}

		$bResult=$oDb->query('INSERT INTO '.AccessModel::F()->query()->getTablePrefix().
				'access(role_id,node_id,access_parentid,access_level)SELECT a.role_id,b.node_id,b.node_parentid,b.node_level FROM '.
				RoleModel::F()->query()->getTablePrefix().'role a,'.
				NodeModel::F()->query()->getTablePrefix().'node b WHERE '.'a.role_id='.$nGroupId.' AND b.node_id in('.$arrActionIdList.')');
		if($bResult===false){
			return false;
		}else{
			return true;
		}
	}

	public function getGroupUserList($nGroupId){
		$oDb=$this->getDb();

		return $oDb->getAllRows('SELECT b.user_id,b.user_nikename,b.user_email FROM '.
				UserroleModel::F()->query()->getTablePrefix().'userrole AS a ,'.
				UserModel::F()->query()->getTablePrefix().'user AS b WHERE a.user_id=b.user_id AND a.role_id='.$nGroupId);
	}

	public function delGroupUser($nGroupId,$arrThispageuser=array()){
		$oDb=$this->getDb();

		if(!empty($arrThispageuser)){
			$sUserWhere=' AND user_id IN('.implode(',',$arrThispageuser).')';
		}else{
			$sUserWhere='';
		}

		$bResult=$oDb->query('DELETE FROM '.UserroleModel::F()->query()->getTablePrefix().'userrole WHERE role_id='.$nGroupId.$sUserWhere);
		if($bResult===false){
			return false;
		}else{
			return true;
		}
	}

	public function setGroupUsers($nGroupId,$arrUserIdList){
		$oDb=$this->getDb();

		if(empty($arrUserIdList)){
			return true;
		}

		if(is_string($arrUserIdList)){
			$arrUserIdList=explode(',',$arrUserIdList);
		}

		array_walk($arrUserIdList,array($this,'fieldFormat'));
		$arrUserIdList=implode(',',$arrUserIdList);
		$bResult=$oDb->query('INSERT INTO '.UserroleModel::F()->query()->getTablePrefix().
					'userrole(role_id,user_id)SELECT a.role_id,b.user_id FROM '.
					RoleModel::F()->query()->getTablePrefix().'role a,'.
					UserModel::F()->query()->getTablePrefix().'user b WHERE '.'a.role_id='.$nGroupId.' AND b.user_id in('.$arrUserIdList.')');
		if($bResult===false){
			return false;
		}else{
			return true;
		}
	}

	public function getParentRole($nParentRoleId){
		if($nParentRoleId==0){
			return Dyhb::L('顶级分类','__COMMON_LANG__@Model/Role');
		}else{
			$oRole=self::F('role_id=?',$nParentRoleId)->query();
			if(!empty($oRole->role_id)){
				return $oRole->role_name;
			}else{
				return Dyhb::L('父级分类已经损坏，你可以编辑分类进行修复','__COMMON_LANG__@Model/Role');
			}
		}
	}

	public function safeInput(){
		$_POST['role_name']=G::html($_POST['role_name']);
		$_POST['role_nikename']=G::html($_POST['role_nikename']);
		$_POST['role_remark']=G::html($_POST['role_remark']);
	}

	protected function fieldFormat(&$Value){
		if(is_int($Value)){
			$Value=intval($Value);
		}else if(is_float($Value)){
			$Value=floatval($Value);
		}else if(is_string($Value)){
			$Value='"'.addslashes($Value).'"';
		}

		return $Value;
	}

}
