<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组成员控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UserController extends Controller{

	public function index(){
		$sId=trim(G::getGpc('gid','G'));
		if(Core_Extend::isPostInt($sId)){
			$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}else{
			$oGroup=GroupModel::F('group_name=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}

		if(empty($oGroup['group_id'])){
			$this->E('小组不存在或在审核中');
		}

	// 取得用户是否加入了小组
		$nGroupuser=Group_Extend::getGroupuser($oGroup['group_id']);
		$this->assign('nGroupuser',$nGroupuser);
		
		// 读取小组创始人
		$arrGroupleaders=GroupuserModel::F('group_id=? AND groupuser_isadmin=2',$oGroup['group_id'])->order('create_dateline DESC')->getAll();

		$this->assign('arrGroupleaders',$arrGroupleaders);

		// 读取小组管理员
		$arrGroupadmins=GroupuserModel::F('group_id=? AND groupuser_isadmin=1',$oGroup['group_id'])->order('create_dateline DESC')->getAll();

		$this->assign('arrGroupadmins',$arrGroupadmins);
		$this->assign('oGroup',$oGroup);

		// 读取成员列表
		$nEverynum=24;
		$arrWhere['group_id']=$oGroup['group_id'];
		$arrWhere['groupuser_isadmin']=0;

		$nTotalRecord=GroupuserModel::F()->where($arrWhere)->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));
		
		$arrGroupusers=GroupuserModel::F()->where($arrWhere)->order("create_dateline DESC")->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrGroupusers',$arrGroupusers);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));

		$this->display('group+user');
	}

}