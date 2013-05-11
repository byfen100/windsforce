<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组新帖控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入个人资料函数 */
require_once(Core_Extend::includeFile('function/Profile_Extend'));

class NewtopicController extends Controller{

	public function index(){	
		Core_Extend::loadCache('sociatype');

		// 新帖列表
		$sType=G::getGpc('type','G'); // 排序类型
		$nDid=intval(G::getGpc('did','G')); // 是否为精华

		if($sType=="view"){
			$sOrderType='grouptopic_views';
		}elseif($sType=="com"){
			$sOrderType='grouptopic_comments';
		}else{
			$sOrderType='create_dateline';
		}

		$this->assign('sType',$sType);

		// 读取帖子列表
		$arrWhere=array();

		$nEverynum=$GLOBALS['_cache_']['group_option']['group_indextopicnum'];
		$arrWhere['grouptopic_status']=1;
		$arrWhere['grouptopic_isaudit']=1;
		$arrWhere['grouptopic_sticktopic']='0';

		if($nDid==1){
			$arrWhere['grouptopic_addtodigest']=array('gt',0);
		}

		$nTotalRecord=GrouptopicModel::F()->where($arrWhere)->all()->getCounts();
		
		$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));
		
		$arrGrouptopics=GrouptopicModel::F()->where($arrWhere)->order(($sType=='lastreply'?'grouptopic_update DESC,':'')."{$sOrderType} DESC")->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		// 全局置顶帖子
		if(isset($arrWhere['grouptopic_addtodigest'])){
			unset($arrWhere['grouptopic_addtodigest']);
		}
		$arrWhere['grouptopic_sticktopic']='3';

		$arrGlobalSticktopics=GrouptopicModel::F()->where($arrWhere)->order(($sType=='lastreply'?'grouptopic_update DESC,':'')."{$sOrderType} DESC")->getAll();

		if(is_array($arrGrouptopics)){
			if(is_array($arrGlobalSticktopics)){
				foreach($arrGlobalSticktopics as $oGlobalSticktopic){
					array_unshift($arrGrouptopics,$oGlobalSticktopic);
				}
			}
		}

		$this->assign('arrGrouptopics',$arrGrouptopics);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));

		// 用户登录后的积分
		if($GLOBALS['___login___']!==false){
			$arrRatinginfo=UserModel::getUserrating($GLOBALS['___login___']['usercount']['usercount_extendcredit1'],false);
			$this->assign('arrRatinginfo',$arrRatinginfo);

			$oUserInfo=UserModel::F('user_id=?',$GLOBALS['___login___']['usercount'])->getOne();
			$this->assign('oUserInfo',$oUserInfo);
		}

		// 热门帖子
		$arrGrouphottopics=Groupdata_Extend::getGrouphottopic();
		$this->assign('arrGrouphottopics',$arrGrouphottopics);

		// 热门标签
		$arrHottags=Groupdata_Extend::getGrouphotag($GLOBALS['_cache_']['group_option']['newtopic_hottagnum']);
		$this->assign('arrHottags',$arrHottags);

		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['seccode_login_status']);
		$this->assign('nRememberTime',$GLOBALS['_option_']['remember_time']);
		$this->assign('arrBindeds',$GLOBALS['_cache_']['sociatype']);
		$this->assign('nDid',$nDid);

		$this->display('public+newtopic');
	}

	public function newtopic_title_(){
		return Dyhb::L('新帖','Controller/Public');
	}

	public function newtopic_keywords_(){
		return $this->newtopic_title_();
	}

	public function newtopic_description_(){
		return $this->newtopic_title_();
	}

}
