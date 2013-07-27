<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组个人空间基本资料($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入个人信息处理函数 */
require_once(Core_Extend::includeFile('function/Profile_Extend'));

class BaseController extends GlobalchildController{
	
	public $_oUserInfo=null;
	
	public function index(){
		$nId=intval(G::getGpc('id','G'));

		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->_oParentcontroller->wap_mes(Dyhb::L('你指定的用户不存在','Controller'),'',0);
		}else{
			$this->assign('oUserInfo',$oUserInfo);
		}

		$this->assign('nId',$nId);

		$this->_oUserInfo=$oUserInfo;
		
		$this->display('space+index');
	}

	public function index_title_(){
		return $this->_oUserInfo['user_name'].' - '.Dyhb::L('Wap个人空间','Controller');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
