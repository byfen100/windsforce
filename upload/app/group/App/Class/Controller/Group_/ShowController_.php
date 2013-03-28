<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   显示小组控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ShowController extends Controller{

	public function index(){
		$sId=trim(G::getGpc('id','G'));
		$nCid=intval(G::getGpc('cid','G'));
		$nDid=intval(G::getGpc('did','G'));
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

		if(Core_Extend::isPostInt($sId)){
			$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}else{
			$oGroup=GroupModel::F('group_name=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}

		if(empty($oGroup['group_id'])){
			$this->E('小组不存在或在审核中');
		}
		
		// 读取帖子列表
		$arrWhere=array();
		$nEverynum=$GLOBALS['_cache_']['group_option']['group_listtopicnum'];
		if(!empty($nCid)){
			$arrWhere['grouptopiccategory_id']=$nCid;
		}
		if(!empty($nDid)&&$nDid==1){
			$arrWhere['grouptopic_addtodigest']=$nDid;
		}

		$arrWhere['grouptopic_status']=1;
		$arrWhere['grouptopic_isaudit']=1;	
		$arrWhere['group_id']=$oGroup->group_id;

		$nTotalComment=GrouptopicModel::F()->where($arrWhere)->all()->getCounts();

		$oPage=Page::RUN($nTotalComment,$nEverynum,G::getGpc('page','G'));

		$arrGrouptopics=GrouptopicModel::F()->where($arrWhere)->order("{$sType} DESC")->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrGrouptopics',$arrGrouptopics);
		$this->assign('nEverynum',$nEverynum);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		
		// 小组分类
		$arrGrouptopiccategory=GrouptopiccategoryModel::F('group_id=?',$oGroup->group_id)->getAll();
		$arrCid=array();
		foreach($arrGrouptopiccategory as $key=>$oValue){
			array_push($arrCid,$oValue->grouptopiccategory_id);
		}

		$this->assign('arrGrouptopiccategory',$arrGrouptopiccategory);

		// 取得用户是否加入了小组
		$nGroupuser=Group_Extend::getGroupuser($oGroup['group_id']);
		$this->assign('nGroupuser',$nGroupuser);

		$this->assign('oGroup',$oGroup);
		$this->assign('arrCid',$arrCid);
		$this->assign('nCid',$nCid);
		
		$this->display('group+show');
	}

}