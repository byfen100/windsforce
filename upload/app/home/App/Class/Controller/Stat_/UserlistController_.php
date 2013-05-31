<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   用户广场用户列表($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入个人信息处理函数 */
require_once(Core_Extend::includeFile('function/Profile_Extend'));

class UserlistController extends Controller{

	public function index(){
		if(!Home_Extend::getVisiteallowed('siteuserlist')){
			$this->E(Dyhb::L('你没有权限访问会员列表','Controller'));
		}

		$arrWhere=array();
		
		// 搜索关键字
		$sKey=trim(G::getGpc('key'));
		if(!empty($sKey)){
			$arrWhere['user_name']=array('like',"%".$sKey."%");
		}

		$sSortBy=strtoupper(G::getGpc('sort_'))=='ASC'?'ASC':'DESC';
		$sOrder=G::getGpc('order_','G')?G::getGpc('order_','G'):'create_dateline';

		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		// 用户列表
		$sTag=trim(G::getGpc('tag','G'));
		if(!empty($sTag)){
			$oHometag=HometagModel::F('hometag_name=?',$sTag)->getOne();
			if(empty($oHometag['hometag_id'])){
				$this->E(Dyhb::L('用户标签不存在','Controller'));
			}

			$this->assign('oHometag',$oHometag);

			$nTotalRecord=HometagindexModel::F('hometag_id=?',$oHometag['hometag_id'])->all()->getCounts();
			
			$oPage=Page::RUN($nTotalRecord,$arrOptionData['user_list_num'],G::getGpc('page','G'));

			$arrHometagindexs=HometagindexModel::F('hometag_id=?',$oHometag['hometag_id'])->limit($oPage->returnPageStart(),$arrOptionData['user_list_num'])->getAll();

			if(is_array($arrHometagindexs)){
				$arrTempdata=array();
				foreach($arrHometagindexs as $oHometagindex){
					$arrTempdata[]=$oHometagindex['user_id'];
				}
				
				$arrWhere['user_id']=array('in',$arrTempdata);
			}else{
				$this->assign('__JumpUrl__',Dyhb::U('home://stat/userlist'));
				$this->E(Dyhb::L('没有发现任何用户','Controller'));
			}
		}else{
			$nTotalRecord=UserModel::F()->where($arrWhere)->all()->getCounts();

			$oPage=Page::RUN($nTotalRecord,$arrOptionData['user_list_num'],G::getGpc('page','G'));
		}

		$arrUsers=UserModel::F()->where($arrWhere)->order($sOrder.' '.$sSortBy)->limit($oPage->returnPageStart(),$arrOptionData['user_list_num'])->getAll();

		$this->assign('arrUsers',$arrUsers);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('sKey',$sKey);
		
		$this->display('stat+userlist');
	}

	public function userlist_title_(){
		return Dyhb::L('会员列表','Controller');
	}

	public function userlist_keywords_(){
		return $this->userlist_title_();
	}

	public function userlist_description_(){
		return $this->userlist_title_();
	}
	
}
