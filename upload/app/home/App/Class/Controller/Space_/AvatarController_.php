<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   个人空间头像($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AvatarController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));
		if(empty($nId)){
			$nId=$GLOBALS['___login___']['user_id'];
		}
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller/Space'));
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

	public $_oUserInfo=null;

	public function index_title_(){
		return $this->_oUserInfo['user_name'].' - '.Dyhb::L('我的头像','Controller/Space');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
