<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   话题列表($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		$arrWhere=array();

		// 类型
		$sType=trim(G::getGpc('type','G'));
		if(empty($sType)){
			$sType='';
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

		$nEverynum=$GLOBALS['_cache_']['group_option']['group_ucenter_listtopicnum'];

		// 帖子
		$arrWhere['grouptopic_status']=1;
		$arrWhere['grouptopic_isaudit']=1;
		$arrWhere['grouptopic_isanonymous']='0';

		$nTotalGrouptopicnum=GrouptopicModel::F()->where($arrWhere)->all()->getCounts();

		$oPage=Page::RUN($nTotalGrouptopicnum,$nEverynum,G::getGpc('page','G'));

		$arrGrouptopics=GrouptopicModel::F()->where($arrWhere)->order("grouptopic_sticktopic DESC,grouptopic_update DESC,create_dateline DESC")->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrGrouptopics',$arrGrouptopics);
		$this->assign('nEverynum',$nEverynum);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['seccode_publish_status']);

		// 快捷发布帖子
		$oGroup=Dyhb::instance('GroupModel');
		$arrGroups=$oGroup->groupbyUserid($GLOBALS['___login___']['user_id']);

		if(!is_array($arrGroups)){
			$arrGroups='';
		}
			
		$this->assign('arrGroups',$arrGroups);

		// 读取一个小组
		$nLabel=$nGroupid=0;
		if(empty($nGroupid) && isset($arrGroups[0])){
			$nGroupid=$arrGroups[0]->group_id;
			$nLabel=1;
		}

		// 小组分类
		$arrGrouptopiccategorys=array();
		$oGrouptopiccategory=Dyhb::instance('GrouptopiccategoryModel');
		$arrGrouptopiccategorys=$oGrouptopiccategory->grouptopiccategoryByGroupid($nGroupid);
		
		if($nLabel==1){
			$nGroupid='';
		}
		
		$this->assign('arrGrouptopiccategorys',$arrGrouptopiccategorys);
		$this->assign('nGroupid',$nGroupid);

		$this->display('ucentergrouptopic+index');
	}

	public function index_title_(){
		return Dyhb::L('小组用户中心','Controller');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
