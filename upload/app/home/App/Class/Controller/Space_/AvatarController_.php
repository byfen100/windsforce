<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   个人空间头像($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AvatarController extends Controller{

	public $_oUserInfo=null;

	public function index(){
		$nId=intval(G::getGpc('id','G'));
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller'));
		}else{
			$this->assign('oUserInfo',$oUserInfo);
		}

		$this->_oUserInfo=$oUserInfo;

		$arrAvatarInfo=array();
		$arrAvatarInfo=Core_Extend::avatars($nId);

		$this->assign('arrAvatarInfo',$arrAvatarInfo);
		$this->assign('nId',$nId);

		$this->display('space+avatar');
	}

	public function index_title_(){
		return $this->_oUserInfo['user_name'].' - '.Dyhb::L('我的头像','Controller');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
