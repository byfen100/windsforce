<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组个人空间基本资料($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入个人信息处理函数 */
require_once(Core_Extend::includeFile('function/Profile_Extend'));

class BaseController extends Controller{
	
	public $_oUserInfo=null;
	
	public function index(){
		$nId=intval(G::getGpc('id','G'));
		$nDid=intval(G::getGpc('did','G')); // 是否为精华
		$nRecommend=intval(G::getGpc('recommend','G')); // 是否推荐
		$sType=G::getGpc('t','G'); // 排序类型
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller'));
		}else{
			$this->assign('oUserInfo',$oUserInfo);
		}

		$this->assign('nId',$nId);

		$this->_oUserInfo=$oUserInfo;

		// 用户等级名字
		$nUserscore=$oUserInfo->usercount->usercount_extendcredit1;
		$arrRatinginfo=UserModel::getUserrating($nUserscore,false);
		$this->assign('arrRatinginfo',$arrRatinginfo);
		$this->assign('nUserscore',$nUserscore);

		if($sType=="view"){
			$sOrderType='grouptopic_views';
		}elseif($sType=="com"){
			$sOrderType='grouptopic_comments';
		}elseif($sType=="lastreply"){
			$sOrderType='grouptopic_update';
		}else{
			$sOrderType='create_dateline';
		}

		$this->assign('sT',$sType);

		// 读取帖子列表
		$arrWhere=array();
		$nEverynum=$GLOBALS['_cache_']['group_option']['group_space_listtopicnum'];

		if($nDid==1){
			$arrWhere['grouptopic_addtodigest']=array('gt',0);
		}

		if($nRecommend==1){
			$arrWhere['grouptopic_isrecommend']=array('gt',0);
		}

		$arrWhere['user_id']=$nId;

		if($GLOBALS['___login___']!==false && $GLOBALS['___login___']['user_id']==$nId){
			$sOrderextends='grouptopic_status ASC,grouptopic_isaudit ASC,grouptopic_isanonymous ASC,';
			$nYouself=1;
		}else{
			$sOrderextends='';
			$nYouself=0;

			$arrWhere['grouptopic_status']=1;
			$arrWhere['grouptopic_isaudit']=1;
			$arrWhere['grouptopic_isanonymous']='0';
		}

		$nTotalGrouptopicnum=GrouptopicModel::F()->where($arrWhere)->all()->getCounts();

		$oPage=Page::RUN($nTotalGrouptopicnum,$nEverynum,G::getGpc('page','G'));

		$arrGrouptopics=GrouptopicModel::F()->where($arrWhere)->order($sOrderextends."grouptopic_sticktopic DESC,grouptopic_update DESC,{$sOrderType} DESC")->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrGrouptopics',$arrGrouptopics);
		$this->assign('nEverynum',$nEverynum);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('nYouself',$nYouself);
		
		$this->display('space+index');
	}

	public function index_title_(){
		return $this->_oUserInfo['user_name'].' - '.Dyhb::L('小组个人空间','Controller');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
