<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子喜欢对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class LoveController extends Controller{

	public function index(){
		// 获取参数
		$nId=intval(G::getGpc('id','G'));
	
		$oGrouptopic=GrouptopicModel::F('grouptopic_id=? AND grouptopic_status=1',$nId)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你访问的主题不存在或已删除','Controller'));
		}

		// 判断帖子小组
		$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$oGrouptopic->group_id)->getOne();

		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller'));
		}

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGroup);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		// 添加前检查是否已经喜欢过了
		$oGrouptopiclove=GrouptopicloveModel::F('user_id=? AND grouptopic_id=?',$GLOBALS['___login___']['user_id'],$oGrouptopic['grouptopic_id'])->getOne();
		
		if(!empty($oGrouptopiclove['user_id'])){
			$this->E(Dyhb::L('你已经喜欢过该帖子了，你不可以重复喜欢。','Controller'));
		}
		
		$this->_oGrouptopic=$oGrouptopic;

		$this->assign('oGrouptopic',$oGrouptopic);

		$this->display('grouptopic+love');
	}

}
