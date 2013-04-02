<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组浏览控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ShowController extends Controller{

	protected $_oGroup=null;
	
	public function index(){
		// 获取参数
		$sId=trim(G::getGpc('id','G')); // 小组ID
		$nCid=intval(G::getGpc('cid','G')); // 帖子分类ID
		$nDid=intval(G::getGpc('did','G')); // 是否为精华
		$sType=G::getGpc('type','G'); // 排序类型

		// 判断小组是否存在
		if(Core_Extend::isPostInt($sId)){
			$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}else{
			$oGroup=GroupModel::F('group_name=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}

		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller/Group'));
		}

		$this->_oGroup=$oGroup;
		
		// 需要登录跳转
		Core_Extend::windsforceReferer();
		
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

		// 读取帖子列表
		$arrWhere=array();
		$nEverynum=$GLOBALS['_cache_']['group_option']['group_listtopicnum'];
		
		if($nCid>0){
			$arrWhere['grouptopiccategory_id']=$nCid;
		}

		if($nDid==1){
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
		$this->get_grouptopiccategory($oGroup['group_id']);

		// 取得用户是否加入了小组
		$this->get_groupuser($oGroup['group_id']);

		// 取得小组会员
		$this->get_user($oGroup['group_id']);

		$this->assign('oGroup',$oGroup);
		$this->assign('nCid',$nCid);

		$this->display('group+show');
	}

	protected function get_grouptopiccategory($nGroupid){
		$arrCids=array();
		
		$arrGrouptopiccategorys=GrouptopiccategoryModel::F('group_id=?',$nGroupid)->order('grouptopiccategory_sort ASC')->getAll();
		if(is_array($arrGrouptopiccategorys)){
			foreach($arrGrouptopiccategorys as $key=>$oValue){
				array_push($arrCids,$oValue->grouptopiccategory_id);
			}
		}

		$this->assign('arrCids',$arrCids);
		$this->assign('arrGrouptopiccategorys',$arrGrouptopiccategorys);
	}

	protected function get_groupuser($nGroupid){
		$nGroupuser=Group_Extend::getGroupuser($nGroupid);

		$this->assign('nGroupuser',$nGroupuser);
	}

	protected function get_user($nGroupid){
		// 读取小组创始人
		$arrGroupleaders=GroupuserModel::F('group_id=? AND groupuser_isadmin=2',$nGroupid)->order('create_dateline DESC')->getAll();

		$this->assign('arrGroupleaders',$arrGroupleaders);

		// 读取小组管理员
		$arrGroupadmins=GroupuserModel::F('group_id=? AND groupuser_isadmin=1',$nGroupid)->order('create_dateline DESC')->getAll();

		$this->assign('arrGroupadmins',$arrGroupadmins);

		// 读取最新成员
		$arrGroupusers=GroupuserModel::F('group_id=? AND groupuser_isadmin=0',$nGroupid)->order('create_dateline DESC')->limit(0,$GLOBALS['_cache_']['group_option']['groupshow_newuser_num'])->getAll();

		$this->assign('arrGroupusers',$arrGroupusers);
	}

	public function show_title_(){
		return $this->_oGroup['group_nikename'].' - '.Dyhb::L('小组','Controller/Group');
	}

	public function show_keywords_(){
		return $this->show_title_();
	}

	public function show_description_(){
		return $this->show_title_();
	}

}