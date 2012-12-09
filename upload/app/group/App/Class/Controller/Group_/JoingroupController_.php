<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   加入小组控制器($)*/

!defined('DYHB_PATH') && exit;

class JoingroupController extends Controller{

	public function index(){
		$nGid=G::getGpc('gid','G');
		$oGroup=GroupModel::F('group_id=?',$nGid)->getOne();

		if(empty($nGid)||empty($oGroup->group_id)){
			$this->E("你访问的小组不存在");
		}

		if(empty($GLOBALS['___login___']['user_id'])){
			$this->E("加入小组需登录后才能进行");
		}

		$arrCondition=array('group_id'=>$nGid,'user_id'=>$GLOBALS['___login___']['user_id']);
		$oGroupuser=GroupuserModel::F($arrCondition)->getOne();
		if(!empty($oGroupuser->user_id)){
			$this->E("你已是该小组成员");
		}

		$oGroupuser=new GroupuserModel();
		$oGroupuser->user_id=$GLOBALS['___login___']['user_id'];
		$oGroupuser->group_id=$nGid;
		$oGroupuser->save(0);
		
		if($oGroupuser->isError()){
			$this->E($oGroupuser->getErrorMessage());
		}

		$oGroup=GroupModel::F('group_id=?',$nGid)->getOne();
		$oGroup->group_usernum=GroupuserModel::F('group_id=?',$nGid)->getCounts();
		$oGroup->save(0,'update');
		
		if($oGroup->isError()){
			$this->E($oGroup->getErrorMessage());
		}

		$this->S("恭喜你，成功加入{$oGroup->group_nikename}小组");
	}
}