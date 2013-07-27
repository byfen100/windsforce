<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   加入小组控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class JoingroupController extends Controller{

	public function index(){
		// 获取参数
		$nGid=G::getGpc('gid','G');

		if($GLOBALS['___login___']===false){
			$this->E(Dyhb::L('加入小组需登录后才能进行','Controller'));
		}

		$oGroup=GroupModel::F('group_id=?',$nGid)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller'));
		}

		// 判断是否已经加入了小组
		$arrCondition=array('group_id'=>$nGid,'user_id'=>$GLOBALS['___login___']['user_id']);
		$oGroupuser=GroupuserModel::F($arrCondition)->getOne();
		if(!empty($oGroupuser['user_id'])){
			$this->E(Dyhb::L('你已是该小组成员','Controller'));
		}

		// 判断小组是否关闭了加入会员
		if($oGroup['group_joinway']==1){
			$this->E(Dyhb::L('该小组目前禁止任何人加入','Controller'));
		}

		// 保存数据
		$oGroupuser=new GroupuserModel();
		$oGroupuser->user_id=$GLOBALS['___login___']['user_id'];
		$oGroupuser->group_id=$nGid;
		$oGroupuser->save(0);
		
		if($oGroupuser->isError()){
			$this->E($oGroupuser->getErrorMessage());
		}

		// 更新小组中的用户数量
		Dyhb::instance('GroupModel')->resetUser($nGid);

		$this->S(Dyhb::L('恭喜你，成功加入 %s 小组','Controller',null,$oGroup->group_nikename));
	}
}