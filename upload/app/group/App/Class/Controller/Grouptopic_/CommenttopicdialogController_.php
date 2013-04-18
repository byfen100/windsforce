<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   快捷回复对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class CommenttopicdialogController extends Controller{

	public function index(){
		$nGrouptopicid=intval(G::getGpc('tid','G'));
		$nGrouptopiccommentid=intval(G::getGpc('cid','G'));

		if(!$nGrouptopicid){
			$this->E(Dyhb::L('你没有指定回复的帖子的ID','Controller/Grouptopic'));
		}

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopicid)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你要回复的帖子不存在','Controller/Grouptopic'));
		}

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGrouptopic['group_id'],true);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		if($nGrouptopiccommentid){
			$oGrouptopiccomment=GrouptopiccommentModel::F('grouptopiccomment_id=?',$nGrouptopiccommentid)->getOne();
			if(empty($oGrouptopiccomment['grouptopiccomment_id'])){
				$this->E(Dyhb::L('你要回复的回帖不存在','Controller/Grouptopic'));
			}

			$this->assign('oGrouptopiccomment',$oGrouptopiccomment);
		}

		// 取得个人主页
		$oUserprofile=UserprofileModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getOne();

		$this->assign('oGrouptopic',$oGrouptopic);
		$this->assign('nDialog',1);
		$this->assign('sUsersite',$oUserprofile['userprofile_site']);

		$this->display('grouptopic+commenttopicdialog');
	}

}
