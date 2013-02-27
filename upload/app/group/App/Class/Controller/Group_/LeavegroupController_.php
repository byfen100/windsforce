<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   离开小组控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class LeavegroupController extends Controller{

	public function index(){
		$nGid=G::getGpc('gid','G');
		$oGroup=GroupModel::F('group_id=?',$nGid)->getOne();

		if(empty($nGid)||empty($oGroup->group_id)){
			$this->E("你访问的小组不存在");
		}

		$arrCondition=array('group_id'=>$nGid,'user_id'=>$GLOBALS['___login___']['user_id']);
		$oGroupuser=GroupuserModel::F($arrCondition)->getOne();
		if(empty($oGroupuser->user_id)){
			$this->E("你尚未加入该小组");
		}

		$oGroupuser->destroy();

		$this->S("成功退出{$oGroup->group_nikename}小组");
	}
}