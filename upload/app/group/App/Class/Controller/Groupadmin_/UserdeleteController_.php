<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组用户删除控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UserdeleteController extends Controller{

	public function index(){
		// 获取参数
		$nGroupid=intval(G::getGpc('gid'));
		$nUserid=intval(G::getGpc('uid'));

		$oGroup=GroupModel::F('group_id=?',$nGroupid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在','Controller/Groupadmin'));
		}

		$oUser=UserModel::F('user_id=?',$nUserid)->getOne();
		if(empty($oUser['user_id'])){
			$this->E(Dyhb::L('用户不存在','Controller/Groupadmin'));
		}

		// 删除前判断被删除用户是否为小组长，如果为小组长则不能够被删除
		$oTrygropuuser=GroupuserModel::F('user_id=? AND group_id=? AND groupuser_isadmin=2',$nUserid,$nGroupid)->getOne();

		if(!empty($oTrygropuuser['groupuser_id'])){
			$this->E(Dyhb::L('你不能够删除小组长','Controller/Groupadmin'));
		}
		
		// 执行删除
		$oGroupuserMeta=GroupuserModel::M();
		$oGroupuserMeta->deleteWhere(array('group_id'=>$nGroupid,'user_id'=>$nUserid));

		if($oGroupuserMeta->isError()){
			$this->E($oGroupuserMeta->getErrorMessage());
		}

		Group_Extend::chearGroupuserrole($nUserid);

		// 更新小组中的用户数量
		Dyhb::instance('GroupModel')->resetUser($nGroupid);

		$this->S(Dyhb::L('用户删除成功','Controller/Groupadmin'));
	}

}