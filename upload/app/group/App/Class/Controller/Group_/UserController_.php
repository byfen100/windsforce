<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组成员控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UserController extends Controller{

	protected $_oGroup=null;
	
	public function index(){
		// 获取参数
		$sId=trim(G::getGpc('gid','G'));

		if(Core_Extend::isPostInt($sId)){
			$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}else{
			$oGroup=GroupModel::F('group_name=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}

		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller/Group'));
		}

		$this->_oGroup=$oGroup;

		// 取得用户是否加入了小组
		$this->get_groupuser($oGroup['group_id']);

		// 取得小组会员
		$this->get_user($oGroup['group_id']);

		// 读取成员列表
		$nEverynum=$GLOBALS['_cache_']['group_option']['group_listusernum'];
		$arrWhere['group_id']=$oGroup['group_id'];
		$arrWhere['groupuser_isadmin']=0;

		$nTotalRecord=GroupuserModel::F()->where($arrWhere)->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));
		
		$arrGroupusers=GroupuserModel::F()->where($arrWhere)->order("create_dateline DESC")->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrGroupusers',$arrGroupusers);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));

		$this->assign('oGroup',$oGroup);

		$this->display('group+user');
	}

	protected function get_groupuser($nGroupid){
		$nGroupuser=Group_Extend::getGroupuser($nGroupid);

		$this->assign('nGroupuser',$nGroupuser);
	}

	protected function get_user($nGroupid){
		// 读取小组创始人
		$arrGroupleaders=GroupuserModel::F('group_id=? AND groupuser_isadmin=2',$nGroupid)->order('create_dateline DESC')->getAll();

		$this->assign('arrGroupleaders',$arrGroupleaders);

		// 读取小组管理员
		$arrGroupadmins=GroupuserModel::F('group_id=? AND groupuser_isadmin=1',$nGroupid)->order('create_dateline DESC')->getAll();

		$this->assign('arrGroupadmins',$arrGroupadmins);
	}

	public function user_title_(){
		return Dyhb::L('小组成员','Controller/Group').' - '.$this->_oGroup['group_nikename'];
	}

	public function user_keywords_(){
		return $this->user_title_();
	}

	public function user_description_(){
		return $this->user_title_();
	}

}