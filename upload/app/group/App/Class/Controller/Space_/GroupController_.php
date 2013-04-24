<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组个人空间小组($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GroupController extends Controller{
	
	public $_oUserInfo=null;
	
	public function index(){
		$nId=intval(G::getGpc('id','G'));
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller/Space'));
		}else{
			$this->assign('oUserInfo',$oUserInfo);
		}

		$this->assign('nId',$nId);

		$this->_oUserInfo=$oUserInfo;

		// 读取我加入的所有小组
		$arrGroupusers=GroupuserModel::F('user_id=?',$nId)->order('create_dateline DESC')->getAll();
		if(is_array($arrGroupusers)){
			// 查询条件
			$arrWhere=array();
			$arrWhere['group_status']=1;
			$arrWhere['group_isaudit']=1;

			$arrTempdata=array();
			foreach($arrGroupusers as $oGroupuser){
				$arrTempdata[]=$oGroupuser['group_id'];
			}
			
			$arrWhere['group_id']=array('in',$arrTempdata);
			
			$arrGroups=GroupModel::F()->where($arrWhere)->order('group_isrecommend DESC,create_dateline DESC')->getAll();
		}else{
			$arrGroups=null;
		}

		$this->assign('arrGroups',$arrGroups);
		
		$this->display('space+group');
	}

	public function index_title_(){
		return $this->_oUserInfo['user_name'].' - '.Dyhb::L('我的小组','Controller/Space');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
