<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组个人空间回帖($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CommentController extends Controller{
	
	public $_oUserInfo=null;
	
	public function index(){
		$nId=intval(G::getGpc('id','G'));
		
		$oUserInfo=UserModel::F()->getByuser_id($nId);
		if(empty($oUserInfo['user_id'])){
			$this->E(Dyhb::L('你指定的用户不存在','Controller/Space'));
		}else{
			$this->assign('oUserInfo',$oUserInfo);
		}

		$this->assign('nId',$nId);

		$this->_oUserInfo=$oUserInfo;

		// 读取回帖列表
		$arrWhere=array();
		$nEverynum=$GLOBALS['_cache_']['group_option']['group_space_listcommentnum'];

		$arrWhere['user_id']=$nId;

		if($GLOBALS['___login___']!==false && $GLOBALS['___login___']['user_id']==$nId){
			$sOrderextends='grouptopiccomment_status ASC,grouptopiccomment_auditpass ASC,grouptopiccomment_ishide DESC,';
			$nYouself=1;
		}else{
			$sOrderextends='';
			$nYouself=0;

			$arrWhere['grouptopiccomment_status']=1;
			$arrWhere['grouptopiccomment_auditpass']=1;
			$arrWhere['grouptopiccomment_ishide']='0';
		}

		$nTotalGrouptopiccommentnum=GrouptopiccommentModel::F()->where($arrWhere)->all()->getCounts();

		$oPage=Page::RUN($nTotalGrouptopiccommentnum,$nEverynum,G::getGpc('page','G'));

		$arrGrouptopiccomments=GrouptopiccommentModel::F()->where($arrWhere)->order($sOrderextends."create_dateline DESC")->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrGrouptopiccomments',$arrGrouptopiccomments);
		$this->assign('nEverynum',$nEverynum);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('nYouself',$nYouself);
		
		$this->display('space+comment');
	}

	public function index_title_(){
		return $this->_oUserInfo['user_name'].' - '.Dyhb::L('我的回帖','Controller/Space');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
