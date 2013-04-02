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
			$sOrderType='create_dateline';
		}elseif($sType=="view"){
			$sOrderType='grouptopic_views';
		}elseif($sType=="com"){
			$sOrderType='grouptopic_comments';
		}else{
			$sOrderType='create_dateline';
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

// 需要登录跳转
		Core_Extend::windsforceReferer();

		
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

		$arrGrouptopics=GrouptopicModel::F()->where($arrWhere)->order("{$sOrderType} DESC")->limit($oPage->returnPageStart(),$nEverynum)->getAll();

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

		// 读取小组创始人
		$arrGroupleaders=GroupuserModel::F('group_id=? AND groupuser_isadmin=2',$oGroup['group_id'])->order('create_dateline DESC')->getAll();

		$this->assign('arrGroupleaders',$arrGroupleaders);

		// 读取小组管理员
		$arrGroupadmins=GroupuserModel::F('group_id=? AND groupuser_isadmin=1',$oGroup['group_id'])->order('create_dateline DESC')->getAll();

		$this->assign('arrGroupadmins',$arrGroupadmins);

		// 读取最新成员
		$arrGroupusers=GroupuserModel::F('group_id=? AND groupuser_isadmin=0',$oGroup['group_id'])->order('create_dateline DESC')->limit(0,6)->getAll();

		$this->assign('arrGroupusers',$arrGroupusers);

		$this->assign('oGroup',$oGroup);
		$this->assign('arrCid',$arrCid);
		$this->assign('nCid',$nCid);
		
		$this->display('group+show');
	}

}