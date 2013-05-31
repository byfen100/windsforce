<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   离开小组控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class LeavegroupController extends Controller{

	public function index(){
		// 获取参数
		$nGid=G::getGpc('gid','G');

		if($GLOBALS['___login___']===false){
			$this->E(Dyhb::L('退出小组需登录后才能进行','Controller'));
		}
		
		$oGroup=GroupModel::F('group_id=?',$nGid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller'));
		}

		// 查询用户是否加入小组
		$arrCondition=array('group_id'=>$nGid,'user_id'=>$GLOBALS['___login___']['user_id']);

		$oGroupuser=GroupuserModel::F($arrCondition)->getOne();
		if(empty($oGroupuser['user_id'])){
			$this->E(Dyhb::L('你尚未加入该小组','Controller'));
		}

		$oGroupuser->destroy();

		// 更新小组中的用户数量
		Dyhb::instance('GroupModel')->resetUser($nGid);

		Group_Extend::chearGroupuserrole($GLOBALS['___login___']['user_id']);

		$this->S(Dyhb::L('成功退出 %s 小组','Controller',null,$oGroup->group_nikename));
	}

}