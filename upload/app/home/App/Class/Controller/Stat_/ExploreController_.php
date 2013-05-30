<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   广场随便看看($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ExploreController extends Controller{

	protected $_sHomefreshtag='';
	protected $_sAtusername='';
	
	public function index(){
		if(!Home_Extend::getVisiteallowed('siteexplore')){
			$this->E(Dyhb::L('你没有权限访问随便看看','Controller'));
		}
		
		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		$arrWhere=array();
		$arrWhere['homefresh_status']=1;

		// 话题
		$sKey=trim(G::getGpc('key','G'));
		if(!empty($sKey)){
			$oHomefreshtag=HomefreshtagModel::F('homefreshtag_status=1 AND homefreshtag_name=?',$sKey)->getOne();
			if(empty($oHomefreshtag['homefreshtag_id'])){
				$this->assign('__JumpUrl__',Dyhb::U('home://stat/explore'));
				$this->E(Dyhb::L('话题不存在或者被禁止了','Controller'));
			}

			$arrWhere['homefresh_message']=array('like',"%[TAG]#{$sKey}#[/TAG]%");
			$this->assign('oHomefreshtag',$oHomefreshtag);
			$this->_sHomefreshtag=$oHomefreshtag['homefreshtag_name'];
		}

		// @user_name
		$sAtusername=trim(G::getGpc('at','G'));
		if(!empty($sAtusername)){
			$oUser=UserModel::F('user_status=1 AND user_name=?',$sAtusername)->getOne();
			if(empty($oUser['user_id'])){
				$this->assign('__JumpUrl__',Dyhb::U('home://stat/explore'));
				$this->E(Dyhb::L('用户不存在或者被禁止了','Controller'));
			}

			$arrWhere['homefresh_message']=array('like',"%[MESSAGE]@{$sAtusername}[/MESSAGE]%");
			$this->assign('oAtuser',$oUser);
			$this->_sAtusername=$oUser['user_name'];
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
		
		// 热门话题
		$this->get_homefreshtag_();
		
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
		$sTitle='';

		if($this->_sHomefreshtag){
			$sTitle=$this->_sHomefreshtag.' | ';
		}elseif($this->_sAtusername){
			$sTitle='@'.$this->_sAtusername.' | ';
		}
		
		return $sTitle.Dyhb::L('随便看看','Controller');
	}

	public function explore_keywords_(){
		return $this->explore_title_();
	}

	public function explore_description_(){
		return $this->explore_title_();
	}

	protected function get_homefreshtag_(){
		$nHomefreshucenterhottagnum=intval($GLOBALS['_cache_']['home_option']['homefresh_ucenterhottagnum']);
		$nDate=intval($GLOBALS['_cache_']['home_option']['home_hothomefreshtag_date']);

		// 读取热门话题
		$arrHothomefreshtags=Homefresh_Extend::getHomefreshtagBydate($nDate,$nHomefreshucenterhottagnum);

		$this->assign('arrHothomefreshtags',$arrHothomefreshtags);
	}
	
}
