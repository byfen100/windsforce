<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   广场随便看看($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ExploreController extends Controller{

	protected $_sHomefreshtag='';
	
	public function index(){
		if(!Home_Extend::getVisiteallowed('siteexplore')){
			$this->E(Dyhb::L('你没有权限访问随便看看','Controller/Stat'));
		}
		
		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		$arrWhere=array();
		$arrWhere['homefresh_status']=1;

		// 话题
		$sKey=trim(G::getGpc('key','G'));
		if(!empty($sKey)){
			$oHomefreshtag=HomefreshtagModel::F('homefreshtag_status=1 AND homefreshtag_name=?',$sKey)->getOne();
			if(empty($oHomefreshtag['homefreshtag_id'])){
				$this->assign('__JumpUrl__',Dyhb::U('home://ucenter/index'));
				$this->E(Dyhb::L('话题不存在或者被禁止了','Controller/Stat'));
			}

			$arrWhere['homefresh_message']=array('like',"%[TAG]#{$sKey}#[/TAG]%");
			$this->assign('oHomefreshtag',$oHomefreshtag);
			$this->_sHomefreshtag=$oHomefreshtag['homefreshtag_name'];
		}

		// 读取新鲜事
		$nTotalRecord=HomefreshModel::F()->where($arrWhere)->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,$arrOptionData['explorefresh_list_num'],G::getGpc('page','G'));
		$arrHomefreshs=HomefreshModel::F()->where($arrWhere)->order('create_dateline DESC')->limit($oPage->returnPageStart(),$arrOptionData['explorefresh_list_num'])->getAll();

		$sGoodCookie=Dyhb::cookie('homefresh_goodnum');
		$arrGoodCookie=explode(',',$sGoodCookie);

		$this->assign('arrGoodCookie',$arrGoodCookie);
		$this->assign('nTotalRecord',$nTotalRecord);
		$this->assign('arrHomefreshs',$arrHomefreshs);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		
		// 取得活跃会员
		$this->get_activeuser_();

		// 取得最新用户
		$this->get_newuser_();
		
		$this->display('stat+explore');
	}

	protected function get_activeuser_(){
		$arrActiveusers=Home_Extend::getActiveuser();
		$this->assign('arrActiveusers',$arrActiveusers);
	}

	protected function get_newuser_(){
		$arrNewusers=Home_Extend::getNewuser();
		$this->assign('arrNewusers',$arrNewusers);
	}

	public function explore_title_(){
		return ($this->_sHomefreshtag?$this->_sHomefreshtag.' | ':'').
			Dyhb::L('随便看看','Controller/Stat');
	}

	public function explore_keywords_(){
		return $this->explore_title_();
	}

	public function explore_description_(){
		return $this->explore_title_();
	}
	
}
