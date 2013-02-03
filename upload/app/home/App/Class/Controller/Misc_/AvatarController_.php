<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   头像控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入个人信息函数库 */
require(Core_Extend::includeFile('function/Profile_Extend'));

class AvatarController extends Controller{

	public function index(){
		$nUserid=intval(G::getGpc('id','G'));

		if(empty($nUserid)){
			return '';
		}

		$oUser=UserModel::F('user_id=?',$nUserid)->getOne();
		if(empty($oUser['user_id'])){
			return '';
		}

		$oUserprofile=UserprofileModel::F('user_id=?',$nUserid)->getOne();

		// 取得性别图标
		$sUsergender=Profile_Extend::getUserprofilegender($oUserprofile['userprofile_gender']);

		$this->assign('oUser',$oUser);
		$this->assign('sUsergender',$sUsergender);
		$this->assign('oUserprofile',$oUserprofile);

		$this->display('misc+avatar');
	}

}
