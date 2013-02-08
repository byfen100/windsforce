<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   新鲜事列表($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	protected $_sHomefreshtag='';

	public function index(){
		$arrWhere=array();

		// 热门话题
		$this->get_homefreshtag_();

		// 话题
		$sKey=trim(G::getGpc('key','G'));
		if(!empty($sKey)){
			$oHomefreshtag=HomefreshtagModel::F('homefreshtag_status=1 AND homefreshtag_name=?',$sKey)->getOne();
			if(empty($oHomefreshtag['homefreshtag_id'])){
				$this->assign('__JumpUrl__',Dyhb::U('home://ucenter/index'));
				$this->E(Dyhb::L('话题不存在或者被禁止了','Controller/Homefresh'));
			}

			$arrWhere['homefresh_message']=array('like',"%[TAG]#{$sKey}#[/TAG]%");
			$this->assign('oHomefreshtag',$oHomefreshtag);
			$this->_sHomefreshtag=$oHomefreshtag['homefreshtag_name'];
		}
		
		// 类型
		if(!empty($oHomefreshtag['homefreshtag_id'])){
			$sType='all';
		}else{
			$sType=trim(G::getGpc('type','G'));
			if(empty($sType)){
				$sType='';
			}
		}
		$this->assign('sType',$sType);

		switch($sType){
			case 'myself':
				$arrWhere['user_id']=$GLOBALS['___login___']['user_id'];
				break;
			case 'friend':
				// 仅好友
				$arrUserIds=FriendModel::getFriendById($GLOBALS['___login___']['user_id']);
			
				if(!empty($arrUserIds)){
					$arrWhere['user_id']=array('in',$arrUserIds);
				}else{
					$arrWhere['user_id']='';
				}
				break;
			case 'all':
				// 这里可以设置用户隐私，比如用户不愿意将动态放出
				break;
			default:
				// 我和好友
				$arrUserIds=FriendModel::getFriendById($GLOBALS['___login___']['user_id']);
				$arrUserIds[]=$GLOBALS['___login___']['user_id'];

				if(!empty($arrUserIds)){
					$arrWhere['user_id']=array('in',$arrUserIds);
				}else{
					$arrWhere['user_id']='';
				}
				break;
		}

		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		// 赞
		$sGoodCookie=Dyhb::cookie('homefresh_goodnum');
		$arrGoodCookie=explode(',',$sGoodCookie);
		$this->assign('arrGoodCookie',$arrGoodCookie);

		// 新鲜事
		$arrWhere['homefresh_status']=1;
		$nTotalRecord=HomefreshModel::F()->where($arrWhere)->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,$arrOptionData['homefresh_list_num'],G::getGpc('page','G'));
		$arrHomefreshs=HomefreshModel::F()->where($arrWhere)->order('create_dateline DESC')->limit($oPage->returnPageStart(),$arrOptionData['homefresh_list_num'])->getAll();

		// 我的新鲜事数量
		$nMyhomefreshnum=Homefresh_Extend::getMyhomefreshnum($GLOBALS['___login___']['user_id']);

		$this->assign('arrHomefreshs',$arrHomefreshs);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['seccode_publish_status']);
		$this->assign('nDisplayCommentSeccode',$GLOBALS['_cache_']['home_option']['seccode_comment_status']);
		$this->assign('nMyhomefreshnum',$nMyhomefreshnum);
		
		$this->display('homefresh+index');
	}

	public function index_title_(){
		return ($this->_sHomefreshtag?$this->_sHomefreshtag.' | ':'').
			Dyhb::L('用户中心','Controller/Homefresh');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

	protected function get_homefreshtag_(){
		$nHomefreshucenterhottagnum=intval($GLOBALS['_cache_']['home_option']['homefresh_ucenterhottagnum']);
		if($nHomefreshucenterhottagnum<1){
			$nHomefreshucenterhottagnum=1;
		}

		$nDate=intval($GLOBALS['_cache_']['home_option']['home_hothomefreshtag_date']);
		if($nDate<3600){
			$nData=3600;
		}
		
		// 读取热门话题
		$arrHothomefreshtags=HomefreshtagModel::F('homefreshtag_status=? AND create_dateline>?',1,CURRENT_TIMESTAMP-$nDate)->order('homefreshtag_totalcount DESC')->limit(0,$nHomefreshucenterhottagnum)->getAll();
		$this->assign('arrHothomefreshtags',$arrHothomefreshtags);
	}

}
