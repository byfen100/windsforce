<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   查看帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入个人信息函数库 */
require(Core_Extend::includeFile('function/Profile_Extend'));

class ViewController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));
		$nPage=intval(G::getGpc('page','G'));
		$nSide=intval(Dyhb::cookie('group_grouptopicside'));
		$nStyle=intval(Dyhb::cookie('group_grouptopicstyle'));
		
		if(!in_array($nStyle,array(1,2))){
			$nStyle=1;
		}
		
		if(!in_array($nSide,array(1,2))){
			$nSide=1;
		}

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nId)->getOne();
		if(empty($oGrouptopic->user_id)){
			$this->E('你访问的主题不存在或已删除');
		}

		$oGrouptopic->grouptopic_views=$oGrouptopic->grouptopic_views+1;
		$oGrouptopic->setAutofill(false);
		$oGrouptopic->save(0,'update');
		if($oGrouptopic->isError()){
			$this->E($oGrouptopic->getErrorMessage());
		}

		$oUserprofile=UserprofileModel::F('user_id=?',$oGrouptopic->user_id)->getOne();
		$this->assign('oUserprofile',$oUserprofile);
		
		// 回复列表
		$arrWhere=array();
		$nEverynum=5;

		$arrWhere['grouptopiccomment_status']=1;
		$arrWhere['grouptopic_id']=$oGrouptopic->grouptopic_id;

		$nTotalComment=GrouptopiccommentModel::F()->where($arrWhere)->all()->getCounts();
		$oPage=Page::RUN($nTotalComment,$nEverynum,G::getGpc('page','G'));
		$arrComments=GrouptopiccommentModel::F()->where($arrWhere)->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('nEverynum',$nEverynum);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('arrComments',$arrComments);
		$this->assign('nPage',$nPage);
		$this->assign('oGrouptopic',$oGrouptopic);

		// 热门帖子
		$arrHotGrouptopics=GrouptopicModel::F('create_dateline>? AND grouptopic_status=?',CURRENT_TIMESTAMP-86400,1)->order('grouptopic_comments DESC')->top(10)->get();
		$this->assign('arrHotGrouptopics',$arrHotGrouptopics);

		// 最新帖子
		$arrNewGrouptopics=GrouptopicModel::F('grouptopic_status=?',1)->order('create_dateline DESC')->limit(0,10)->getAll();
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

}
