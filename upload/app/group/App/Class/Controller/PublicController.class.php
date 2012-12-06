<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   群组首页控制器($)*/

!defined('DYHB_PATH') && exit;

class PublicController extends InitController{

	public function index(){
		$nCid=intval(G::getGpc('cid','G'));

		$arrWhere=array();
		if($nCid){
			$oGroupcategory=GroupcategoryModel::F('groupcategory_id=?',$nCid)->getOne();
			if(empty($oGroupcategory['groupcategory_id'])){
				$this->U('group://public/index');
			}

			$this->assign('oGroupcategory',$oGroupcategory);
			$arrWhere['groupcategory_parentid']=$nCid;
		}else{
			$arrWhere['groupcategory_parentid']=0;
		}
		
		$arrGroupcategorys=GroupcategoryModel::F()->where($arrWhere)->getAll();

		$this->assign('arrGroupcategorys',$arrGroupcategorys);

		$this->display('public+index');
	}

	public function newtopic(){
		Core_Extend::loadCache('sociatype');

		// 新帖列表
		$sType=G::getGpc('type','G');
		if(empty($sType)){
			$sType='create_dateline';
		}elseif($sType=="view"){
			$sType='grouptopic_views';
		}elseif($sType=="com"){
			$sType='grouptopic_comments';
		}else{
			$sType='create_dateline';
		}
		$this->assign('sType',$sType);

		$arrWhere=array();
		$nEverynum=$GLOBALS['_cache_']['group_option']['group_indextopicnum'];
		$arrWhere['grouptopic_status']=1;
		$arrWhere['grouptopic_isaudit']=1;
		$nTotalRecord=GrouptopicModel::F()->where($arrWhere)->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));

		$arrGrouptopics=GrouptopicModel::F()->where($arrWhere)->order("{$sType} DESC")->limit($oPage->returnPageStart(),$nEverynum)->getAll();
		$this->assign('arrGrouptopics',$arrGrouptopics);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));

		// 热门帖子
		$nGrouphottopicdate=$GLOBALS['_cache_']['group_option']['group_hottopic_date'];
		if($nGrouphottopicdate<3600){
			$nGrouphottopicdate=3600;
		}

		$nGrouphottopicnum=$GLOBALS['_cache_']['group_option']['group_hottopic_num'];
		if($nGrouphottopicnum<1){
			$nGrouphottopicnum=1;
		}
		
		$arrGrouphottopics=GrouptopicModel::F('create_dateline>?',CURRENT_TIMESTAMP-$nGrouphottopicdate)->order('grouptopic_comments DESC')->top($nGrouphottopicnum)->get();
		$this->assign('arrGrouphottopics',$arrGrouphottopics);

		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['seccode_login_status']);
		$this->assign('nRememberTime',$GLOBALS['_option_']['remember_time']);
		$this->assign('arrBindeds',$GLOBALS['_cache_']['sociatype']);

		$this->display('public+newtopic');
	}

	public function add(){
		$this->display('public+add');
	}

	public function unserialize($slatestcomment){
		$arrLatestcomment=unserialize($slatestcomment);
		return $arrLatestcomment;
	}
}
