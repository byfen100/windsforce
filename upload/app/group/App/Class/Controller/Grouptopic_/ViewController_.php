<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   查看帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入个人信息函数库 */
require(Core_Extend::includeFile('function/Profile_Extend'));

class ViewController extends Controller{

	protected $_oGrouptopic=null;
	
	public function index(){
		// 获取参数
		$nId=intval(G::getGpc('id','G')); // 帖子ID
		$nPage=intval(G::getGpc('page','G')); // 分页数量
		
		$nSide=Dyhb::cookie('group_grouptopicside')?intval(Dyhb::cookie('group_grouptopicside')):$GLOBALS['_cache_']['group_option']['group_grouptopicside']; // 帖子包含侧边栏
		$nStyle=Dyhb::cookie('group_grouptopicstyle')?intval(Dyhb::cookie('group_grouptopicstyle')):$GLOBALS['_cache_']['group_option']['group_grouptopicstyle']; // 帖子是否为新风格
		
		if(!in_array($nSide,array(1,2))){
			$nSide=1;
		}
		if(!in_array($nStyle,array(1,2))){
			$nStyle=1;
		}

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=? AND grouptopic_status=1',$nId)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你访问的主题不存在或已删除','Controller/Grouptopic'));
		}

		// 需要登录跳转
		Core_Extend::windsforceReferer();

		$this->assign('oGrouptopic',$oGrouptopic);

		$this->_oGrouptopic=$oGrouptopic;

		// 更新点击量
		$oGrouptopic->grouptopic_views=$oGrouptopic->grouptopic_views+1;
		$oGrouptopic->setAutofill(false);
		$oGrouptopic->save(0,'update');

		if($oGrouptopic->isError()){
			$this->E($oGrouptopic->getErrorMessage());
		}

		// 读取用户个人资料
		$oUserprofile=UserprofileModel::F('user_id=?',$oGrouptopic->user_id)->getOne();
		
		$this->assign('oUserprofile',$oUserprofile);
		
		// 回复列表
		$arrWhere=array();
		$nEverynum=$GLOBALS['_cache_']['group_option']['grouptopic_listcommentnum'];

		$arrWhere['grouptopiccomment_status']=1;
		$arrWhere['grouptopic_id']=$oGrouptopic->grouptopic_id;

		$nTotalComment=GrouptopiccommentModel::F()->where($arrWhere)->all()->getCounts();

		$oPage=Page::RUN($nTotalComment,$nEverynum,G::getGpc('page','G'));

		$arrComments=GrouptopiccommentModel::F()->where($arrWhere)->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('nEverynum',$nEverynum);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('arrComments',$arrComments);
		$this->assign('nPage',$nPage);

		// 热门帖子
		$arrHotGrouptopics=GrouptopicModel::F('create_dateline>? AND grouptopic_status=?',CURRENT_TIMESTAMP-86400,1)->order('grouptopic_comments DESC')->top($GLOBALS['_cache_']['group_option']['grouptopic_hotnum'])->get();
		
		$this->assign('arrHotGrouptopics',$arrHotGrouptopics);

		// 最新帖子
		$arrNewGrouptopics=GrouptopicModel::F('grouptopic_status=?',1)->order('create_dateline DESC')->limit(0,$GLOBALS['_cache_']['group_option']['grouptopic_newnum'])->getAll();
		
		$this->assign('arrNewGrouptopics',$arrNewGrouptopics);

		$this->assign('nStyle',$nStyle);
		$this->assign('nSide',$nSide);

		if($nStyle==2){
			$this->display('grouptopic+viewnew');
		}else{
			$this->display('grouptopic+view');
		}
	}

	public function totalTopic($nUserid,$bAddtodigest=false){
		if($bAddtodigest===false){
			return GrouptopicModel::F('user_id=?',$nUserid)->getCounts();
		}else{
			return GrouptopicModel::F('user_id=? AND grouptopic_addtodigest=1',$nUserid)->getCounts();
		}
	}

	public function totalComment($nUserid){
		return $nGrouptopic=GrouptopiccommentModel::F('user_id=?',$nUserid)->getCounts();
	}

	public function get_commentfloor($nIndex,$nEverynum){
		$nPage=intval(G::getGpc('page','G'));

		if($nPage<2){
			return $nIndex;
		}else{
			return ($nPage-1)*$nEverynum+$nIndex;
		}
	}

	public function view_title_(){
		return $this->_oGrouptopic['grouptopic_title'].' - '.$this->_oGrouptopic->group->group_nikename;
	}

	public function view_keywords_(){
		return $this->view_title_();
	}

	public function view_description_(){
		return $this->view_title_();
	}

}
