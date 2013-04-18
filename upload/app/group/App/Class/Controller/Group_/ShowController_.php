<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组浏览控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ShowController extends Controller{

	protected $_oGroup=null;
	protected $_sGroupcategory='';
	
	public function index(){
		// 获取参数
		$sId=trim(G::getGpc('id','G')); // 小组ID
		$nCid=intval(G::getGpc('cid','G')); // 帖子分类ID
		$nDid=intval(G::getGpc('did','G')); // 是否为精华
		$nRecommend=intval(G::getGpc('recommend','G')); // 是否推荐
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

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGroup);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		$this->_oGroup=$oGroup;
		
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
			$oCurrentcategory=GrouptopiccategoryModel::F('grouptopiccategory_id=?',$nCid)->getOne();
			
			if(!empty($oCurrentcategory['grouptopiccategory_id'])){
				$arrWhere['grouptopiccategory_id']=$nCid;
				$this->_sGroupcategory=$oCurrentcategory['grouptopiccategory_name'];
				$this->assign('oCurrentcategory',$oCurrentcategory);
			}
		}

		if($nCid==-1){
			$this->_sGroupcategory=Dyhb::L('默认分类','Controller/Group');
			$arrWhere['grouptopiccategory_id']='0';
		}

		if($nDid==1){
			$arrWhere['grouptopic_addtodigest']=array('gt',0);
		}

		if($nRecommend==1){
			$arrWhere['grouptopic_isrecommend']=array('gt',0);
		}

		$arrWhere['grouptopic_status']=1;
		$arrWhere['group_id']=$oGroup->group_id;
		$arrWhere['grouptopic_sticktopic']=array('lt',3);

		if(Groupadmin_Extend::checkTopicadminRbac($oGroup,array('group@grouptopicadmin@hidetopic'))){
			$sOrderextends='grouptopic_isaudit ASC,';
		}else{
			$sOrderextends='';
			$arrWhere['grouptopic_isaudit']=1;
		}

		$nTotalGrouptopicnum=GrouptopicModel::F()->where($arrWhere)->all()->getCounts();

		$oPage=Page::RUN($nTotalGrouptopicnum,$nEverynum,G::getGpc('page','G'));

		$arrGrouptopics=GrouptopicModel::F()->where($arrWhere)->order($sOrderextends."grouptopic_sticktopic DESC,update_dateline DESC,{$sOrderType} DESC")->limit($oPage->returnPageStart(),$nEverynum)->getAll();
		
		// 全局置顶帖子
		if(isset($arrWhere['grouptopic_addtodigest'])){
			unset($arrWhere['grouptopic_addtodigest']);
		}

		if(isset($arrWhere['grouptopiccategory_id'])){
			unset($arrWhere['grouptopiccategory_id']);
		}

		if(isset($arrWhere['group_id'])){
			unset($arrWhere['group_id']);
		}

		$arrWhere['grouptopic_sticktopic']='3';

		$arrGlobalSticktopics=GrouptopicModel::F()->where($arrWhere)->order(($sType=='lastreply'?'update_dateline DESC,':'')."{$sOrderType} DESC")->getAll();

		if(is_array($arrGrouptopics)){
			if(is_array($arrGlobalSticktopics)){
				foreach($arrGlobalSticktopics as $oGlobalSticktopic){
					array_unshift($arrGrouptopics,$oGlobalSticktopic);
				}
			}
		}

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
		$this->assign('nDid',$nDid);
		$this->assign('nRecommend',$nRecommend);

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
		$nGroupuser=Groupadmin_Extend::getGroupuser($nGroupid);

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
		return ($this->_sGroupcategory?$this->_sGroupcategory.' - ':'').$this->_oGroup['group_nikename'].' - '.Dyhb::L('小组','Controller/Group');
	}

	public function show_keywords_(){
		return $this->show_title_();
	}

	public function show_description_(){
		return $this->show_title_();
	}

}