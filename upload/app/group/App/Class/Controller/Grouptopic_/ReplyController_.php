<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加帖子回复控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入home应用配置值 */
if(!Dyhb::classExists('HomeoptionModel')){
	require_once(WINDSFORCE_PATH.'/app/home/App/Class/Model/HomeoptionModel.class.php');
}

/** 载入home应用配置信息 */
if(!isset($GLOBALS['_cache_']['home_option'])){
	Core_Extend::loadCache('home_option');
}

class ReplyController extends Controller{

	public function index(){
		try{
			Core_Extend::checkSpam();
		}catch(Exception $e){
			$this->E($e->getMessage());
		}
		
		$nId=intval(G::getGpc('id','G'));
		
		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定主题的ID','Controller'));
		}

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=? AND grouptopic_status=1',$nId)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你访问的主题不存在或已删除','Controller'));
		}

		$this->assign('oGrouptopic',$oGrouptopic);

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGrouptopic['group_id'],true);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		$this->assign('oGroup',$oGrouptopic->group);

		// 取得用户是否加入了小组
		$this->get_groupuser($oGrouptopic->group_id);

		// 取得个人主页
		$oUserprofile=UserprofileModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getOne();
		
		$this->assign('sUsersite',$oUserprofile['userprofile_site']);
		$this->assign('nDisplaySeccode',$GLOBALS['_cache_']['home_option']['seccode_comment_status']);

		$this->display('grouptopic+reply');
	}

	protected function get_groupuser($nGroupid){
		$nGroupuser=Groupadmin_Extend::getGroupuser($nGroupid);

		$this->assign('nGroupuser',$nGroupuser);
	}

	public function reply_title_(){
		return Dyhb::L('帖子回复','Controller');
	}

	public function reply_keywords_(){
		return $this->reply_title_();
	}

	public function reply_description_(){
		return $this->reply_title_();
	}

}
