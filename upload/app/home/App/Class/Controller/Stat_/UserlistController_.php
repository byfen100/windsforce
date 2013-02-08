<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   用户广场用户列表($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UserlistController extends Controller{

	public function index(){
		if(!Home_Extend::getVisiteallowed('siteuserlist')){
			$this->E(Dyhb::L('你没有权限访问会员列表','Controller/Stat'));
		}
		
		require_once(Core_Extend::includeFile('function/Profile_Extend'));

		$arrWhere=array();
		
		$sKey=trim(G::getGpc('key'));
		if(!empty($sKey)){
			$arrWhere['user_name']=array('like',"%".$sKey."%");
		}
		
		$sSortBy=strtoupper(G::getGpc('sort_'))=='ASC'?'ASC':'DESC';
		$sOrder=G::getGpc('order_','G')?G::getGpc('order_','G'):'create_dateline';

		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		// 用户列表
		$nTotalRecord=UserModel::F()->where($arrWhere)->all()->getCounts();

		$oPage=Page::RUN($nTotalRecord,$arrOptionData['user_list_num'],G::getGpc('page','G'));

		$arrUsers=UserModel::F()->where($arrWhere)->order($sOrder.' '.$sSortBy)->limit($oPage->returnPageStart(),$arrOptionData['user_list_num'])->getAll();
		$this->assign('arrUsers',$arrUsers);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('sKey',$sKey);
		
		$this->display('stat+userlist');
	}

	public function userlist_title_(){
		return Dyhb::L('会员列表','Controller/Stat');
	}

	public function userlist_keywords_(){
		return $this->userlist_title_();
	}

	public function userlist_description_(){
		return $this->userlist_title_();
	}
	
}
