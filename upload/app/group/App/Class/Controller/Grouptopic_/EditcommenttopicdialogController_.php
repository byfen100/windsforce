<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   编辑回复对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入home应用配置值 */
if(!Dyhb::classExists('HomeoptionModel')){
	require_once(WINDSFORCE_PATH.'/app/home/App/Class/Model/HomeoptionModel.class.php');
}

/** 载入home应用配置信息 */
if(!isset($GLOBALS['_cache_']['home_option'])){
	Core_Extend::loadCache('home_option');
}

class EditcommenttopicdialogController extends Controller{

	public function index(){
		$nGrouptopiccommentid=intval(G::getGpc('cid','G'));

		if(!$nGrouptopiccommentid){
			$this->E(Dyhb::L('你没有指定编辑的回帖的ID','Controller'));
		}

		$oGrouptopiccomment=GrouptopiccommentModel::F('grouptopiccomment_id=?',$nGrouptopiccommentid)->getOne();
		if(empty($oGrouptopiccomment['grouptopiccomment_id'])){
			$this->E(Dyhb::L('你要编辑的回帖不存在','Controller'));
		}

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$oGrouptopiccomment['grouptopic_id'])->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你要编辑的回帖的主题不存在','Controller'));
		}

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGrouptopic['group_id'],true);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		// 取得个人主页
		$oUserprofile=UserprofileModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getOne();

		$this->assign('oEditGrouptopiccomment',$oGrouptopiccomment);
		$this->assign('oGrouptopic',$oGrouptopic);
		$this->assign('nDisplaySeccode',$GLOBALS['_cache_']['home_option']['seccode_comment_status']);

		$this->display('grouptopic+editcommenttopicdialog');
	}

}
