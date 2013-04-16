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

		// 判断帖子小组
		$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$oGrouptopic->group_id)->getOne();

		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller/Group'));
		}

		if($oGroup->group_isopen==0){
			$oGroupuser=GroupuserModel::F('user_id=? AND group_id=?',$GLOBALS['___login___']['user_id'],$oGroup['group_id'])->getOne();
			if(empty($oGroupuser['user_id'])){
				$this->E(Dyhb::L('只有该小组成员才能够访问小组','Controller/Group').'&nbsp;<span id="listgroup_'.$oGroup['group_id'].'" class="commonjoinleave_group"><a href="javascript:void(0);" onclick="joinGroup('.$oGroup['group_id'].',\'listgroup_'.$oGroup['group_id'].'\');">'.Dyhb::L('我要加入','Controller/Group').'</a></span>');
			}
		}

		// 判断邮件等外部地址过来的查找评论地址
		$nIsolationCommentid=intval(G::getGpc('isolation_commentid','G'));
		if($nIsolationCommentid){
			$result=GrouptopiccommentModel::getCommenturlByid($nIsolationCommentid);
			if($result===false){
				$this->E(Dyhb::L('该条评论已被删除、屏蔽或者尚未通过审核','Controller/Grouptopic'));
			}

			G::urlGoTo($result);
			exit();
		}
		
		$this->_oGrouptopic=$oGrouptopic;

		$this->assign('oGrouptopic',$oGrouptopic);
		$this->assign('oGroup',$oGrouptopic->group);

		// 取得用户是否加入了小组 && 用户在小组中的角色
		$this->get_groupuser($oGrouptopic->group->group_id);

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
		$this->assign('sUsersite',$oUserprofile['userprofile_site']);

		// 读取帖子标签
		$arrGrouptopictags='';
		
		$arrGrouptopictagindexs=GrouptopictagindexModel::F('grouptopic_id=?',$oGrouptopic['grouptopic_id'])->getAll();

		if(is_array($arrGrouptopictagindexs)){
			$arrTagindex=array();
			foreach($arrGrouptopictagindexs as $oGrouptopictagindex){
				$arrTagindex[]=$oGrouptopictagindex['grouptopictag_id'];
			}
			
			if(!empty($arrTagindex)){
				$arrGrouptopictags=GrouptopictagModel::F()->where(array('grouptopictag_id'=>array('in',$arrTagindex)))->order('create_dateline DESC')->getAll();
			}
		}
		
		$this->assign('arrGrouptopictags',$arrGrouptopictags);

		// 判断用户是否回复过帖子
		if($oGrouptopic['grouptopic_onlycommentview']==1){
			$bHavecomment=false;

			if($GLOBALS['___login___']!==false){
				if($oGrouptopic['user_id']==$GLOBALS['___login___']['user_id']){
					$bHavecomment=true;
				}else{
					$oTrygrouptopiccomment=GrouptopiccommentModel::F('user_id=? AND grouptopic_id=?',$GLOBALS['___login___']['user_id'],$oGrouptopic['grouptopic_id'])->getOne();

					if(!empty($oTrygrouptopiccomment['grouptopiccomment_id'])){
						$bHavecomment=true;
					}
				}
			}

			$this->assign('bHavecomment',$bHavecomment);
		}
		
		// 回复列表
		$arrWhere=array();
		$nEverynum=$GLOBALS['_cache_']['group_option']['grouptopic_listcommentnum'];

		$arrWhere['grouptopiccomment_status']=1;
		$arrWhere['grouptopic_id']=$oGrouptopic->grouptopic_id;

		if(!Group_Extend::checkCommentadminRbac($oGrouptopic->group,array('group@grouptopicadmin@auditcomment'))){
			$arrWhere['grouptopiccomment_auditpass']=1;
		}

		$nTotalComment=GrouptopiccommentModel::F()->where($arrWhere)->all()->getCounts();

		$oPage=Page::RUN($nTotalComment,$nEverynum,G::getGpc('page','G'));

		$arrComments=GrouptopiccommentModel::F()->where($arrWhere)->order('grouptopiccomment_auditpass ASC,grouptopiccomment_stickreply DESC,create_dateline '.($oGrouptopic['grouptopic_ordertype']==1?'DESC':'ASC'))->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('nEverynum',$nEverynum);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('arrComments',$arrComments);
		$this->assign('nPage',$nPage);

		// 读取回帖回收站数量
		$nTotalRecyclebinComment=GrouptopiccommentModel::F()->where(array('grouptopic_id'=>$oGrouptopic->grouptopic_id,'grouptopiccomment_status'=>'0'))->all()->getCounts();
		$this->assign('nTotalRecyclebinComment',$nTotalRecyclebinComment);

		// 热门帖子
		$arrHotGrouptopics=GrouptopicModel::F('create_dateline>? AND grouptopic_status=? AND group_id=?',CURRENT_TIMESTAMP-86400,1,$oGrouptopic['group_id'])->order('grouptopic_comments DESC')->top($GLOBALS['_cache_']['group_option']['grouptopic_hotnum'])->get();
		
		$this->assign('arrHotGrouptopics',$arrHotGrouptopics);

		// 最新帖子
		$arrNewGrouptopics=GrouptopicModel::F('grouptopic_status=? AND group_id=?',1,$oGrouptopic['group_id'])->order('create_dateline DESC')->limit(0,$GLOBALS['_cache_']['group_option']['grouptopic_newnum'])->getAll();
		
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

		if($nPage>=2){
			$nIndex=($nPage-1)*$nEverynum+$nIndex;
		}

		switch($nIndex){
			case 1:
				return Dyhb::L('沙发','Controller/Grouptopic');
				break;
			case 2:
				return Dyhb::L('板凳','Controller/Grouptopic');
				break;
			case 3:
				return Dyhb::L('地板','Controller/Grouptopic');
				break;
			default:
				return $nIndex;
				break;
		}
	}

	protected function get_groupuser($nGroupid){
		$nGroupuser=Group_Extend::getGroupuser($nGroupid);

		$this->assign('nGroupuser',$nGroupuser);
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
