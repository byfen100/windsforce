<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   创建小组逻辑界面控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AddController extends Controller{

	public function index(){
		$nAgreement=intval(G::getGpc('agreement'));

		if(!$nAgreement){
			$this->E(Dyhb::L('你必须同意用户协议中的条款才能够创建小组','Controller'));
		}
		
		// 保存小组
		$oGroup=new GroupModel();

		if($GLOBALS['_cache_']['group_option']['group_isaudit']==1){
			$oGroup->group_isaudit='0';
		}

		$oGroup->save(0);

		if($oGroup->isError()){
			$this->E($oGroup->getErrorMessage());
		}

		if($GLOBALS['_cache_']['group_option']['group_isaudit']==1){
			$sUrl=Dyhb::U('group://space@?id='.$GLOBALS['___login___']['user_id'].'&type=group');
		}else{
			$sUrl=Group_Extend::getGroupurl($oGroup);
		}

		// 小组创建后设置创建者为小组长
		$oGroupuser=new GroupuserModel();
		$oGroupuser->user_id=$GLOBALS['___login___']['user_id'];
		$oGroupuser->group_id=$oGroup['group_id'];
		$oGroupuser->groupuser_isadmin=1;
		$oGroupuser->save(0);

		if($oGroupuser->isError()){
			$this->E($oGroupuser->getErrorMessage());
		}

		// 用户被设置为小组管理员后，判断是否拥有管理员角色
		$oUserrole=UserroleModel::F('user_id=? AND role_id=3',$GLOBALS['___login___']['user_id'])->getOne();
		if(empty($oUserrole['user_id'])){
			Dyhb::instance('RoleModel')->setGroupUsers(3,array($GLOBALS['___login___']['user_id']));
		}

		// 更新小组中的用户数量
		$oGroup->group_usernum=GroupuserModel::F('group_id=?',$oGroup['group_id'])->getCounts();
		$oGroup->setAutofill(false);
		$oGroup->save(0,'update');

		if($oGroup->isError()){
			$this->E($oGroup->getErrorMessage());
		}

		$this->A(array('url'=>$sUrl),Dyhb::L('创建小组成功','Controller').($GLOBALS['_cache_']['group_option']['group_isaudit']==1?'<br/>'.Dyhb::L('注意，你的小组需要审核通过后才能够使用','Controller'):''),1);
	}

}