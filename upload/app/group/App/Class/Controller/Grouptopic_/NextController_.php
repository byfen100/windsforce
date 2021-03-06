<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   查找下一篇帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class NextController extends Controller{

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

		// 读取小组中的下一个帖子
		$oGrouptopicnext=GrouptopicModel::F('grouptopic_id>? AND grouptopic_status=1 AND group_id=?',$nId,$oGroup['group_id'])->order('grouptopic_id ASC')->getOne();
		if(empty($oGrouptopicnext['grouptopic_id'])){
			$this->E(Dyhb::L('没有比这更新的主题了','Controller'));
		}

		$this->U('group://topic@?id='.$oGrouptopicnext['grouptopic_id']);
	}

}
