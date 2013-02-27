<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   新注册会员($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class NewuserController extends Controller{

	public function index(){
		if(!Home_Extend::getVisiteallowed('sitenewuser')){
			$this->E(Dyhb::L('你没有权限访问新注册会员','Controller/Stat'));
		}
		
		$arrOptionData=$GLOBALS['_cache_']['home_option'];
		$nCurrentTimeStamp=CURRENT_TIMESTAMP;

		// 用户列表
		$nTotalRecord=UserModel::F("{$nCurrentTimeStamp}-create_dateline<86400")->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,$arrOptionData['newuser_list_num'],G::getGpc('page','G'));
		$arrNewusers=UserModel::F("{$nCurrentTimeStamp}-create_dateline<86400")->order('create_dateline DESC')->limit($oPage->returnPageStart(),$arrOptionData['newuser_list_num'])->getAll();

		$this->assign('nTotalRecord',$nTotalRecord);
		$this->assign('arrNewusers',$arrNewusers);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		
		$this->display('stat+newuser');
	}

	public function newuser_title_(){
		return Dyhb::L('新注册会员','Controller/Stat');
	}

	public function newuser_keywords_(){
		return $this->newuser_title_();
	}

	public function newuser_description_(){
		return $this->newuser_title_();
	}
	
}
