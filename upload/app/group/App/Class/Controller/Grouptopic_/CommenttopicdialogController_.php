<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   快捷回复对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入home应用配置值 */
if(!Dyhb::classExists('HomeoptionModel')){
	require_once(WINDSFORCE_PATH.'/app/home/App/Class/Model/HomeoptionModel.class.php');
}

/** 载入home应用配置信息 */
if(!isset($GLOBALS['_cache_']['home_option'])){
	Core_Extend::loadCache('home_option');
}

class CommenttopicdialogController extends Controller{

	public function index(){
		$nGrouptopicid=intval(G::getGpc('tid','G'));
		$nGrouptopiccommentid=intval(G::getGpc('cid','G'));

		if(!$nGrouptopicid){
			$this->E(Dyhb::L('你没有指定回复的帖子的ID','Controller'));
		}

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopicid)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你要回复的帖子不存在','Controller'));
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
				$this->E(Dyhb::L('你要回复的回帖不存在','Controller'));
			}

			$this->assign('oGrouptopiccomment',$oGrouptopiccomment);
		}

		// 取得个人主页
		$oUserprofile=UserprofileModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getOne();

		$this->assign('oGrouptopic',$oGrouptopic);
		$this->assign('nDialog',1);
		$this->assign('sUsersite',$oUserprofile['userprofile_site']);
		$this->assign('nDisplaySeccode',$GLOBALS['_cache_']['home_option']['seccode_comment_status']);

		$this->display('grouptopic+commenttopicdialog');
	}

}
