<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   查找上一篇帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class PrevController extends Controller{

	public function index(){
		// 获取参数
		$nId=intval(G::getGpc('id','G'));
	
		$oGrouptopic=GrouptopicModel::F('grouptopic_id=? AND grouptopic_status=1',$nId)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你访问的主题不存在或已删除','Controller/Grouptopic'));
		}

		// 判断帖子小组
		$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$oGrouptopic->group_id)->getOne();

		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller/Grouptopic'));
		}

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGroup);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		// 读取小组中的上一个帖子
		$oGrouptopicprev=GrouptopicModel::F('grouptopic_id<? AND grouptopic_status=1 AND group_id=?',$nId,$oGroup['group_id'])->order('grouptopic_id DESC')->getOne();
		if(empty($oGrouptopicprev['grouptopic_id'])){
			$this->E(Dyhb::L('没有比这更早的主题了','Controller/Grouptopic'));
		}

		$this->U('group://topic@?id='.$oGrouptopicprev['grouptopic_id']);
	}

}
