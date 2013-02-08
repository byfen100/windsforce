<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   查看帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入个人信息函数库 */
require(Core_Extend::includeFile('function/Profile_Extend'));

class ViewController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));
		$nPage=intval(G::getGpc('page','G'));

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
		$arrComment=GrouptopiccommentModel::F()->where($arrWhere)->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('nEverynum',$nEverynum);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('arrComment',$arrComment);
		$this->assign('nPage',$nPage);
		$this->assign('oGrouptopic',$oGrouptopic);

		$this->display('grouptopic+view');
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
