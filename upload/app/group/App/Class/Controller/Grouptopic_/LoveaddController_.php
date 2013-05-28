<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子喜欢处理逻辑控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class LoveaddController extends Controller{

	public function index(){
		// 获取参数
		$nId=intval(G::getGpc('grouptopic_id'));
		$sGrouptopiclovenote=trim(G::getGpc('grouptopiclove_note'));

		if(empty($sGrouptopiclovenote)){
			$sGrouptopiclovenote=Dyhb::L('你没有填写喜欢备注','Controller/Grouptopic');
		}
	
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

		// 添加前检查是否已经喜欢过了
		$oGrouptopiclove=GrouptopicloveModel::F('user_id=? AND grouptopic_id=?',$GLOBALS['___login___']['user_id'],$oGrouptopic['grouptopic_id'])->getOne();
		
		if(!empty($oGrouptopiclove['user_id'])){
			$this->E(Dyhb::L('你已经喜欢过该帖子了，你不可以重复喜欢。','Controller/Grouptopic'));
		}
		
		// 添加处理
		$oGrouptopiclove=new GrouptopicloveModel();
		$oGrouptopiclove->grouptopiclove_note=$sGrouptopiclovenote;
		$oGrouptopiclove->save(0);

		if($oGrouptopiclove->isError()){
			$this->E($oGrouptopiclove->getErrorMessage());
		}else{
			// 更新帖子的喜欢数
			$oGrouptopic->rebuildGrouptopicloves();

			if($oGrouptopic->isError()){
				$this->E($oGrouptopic->getErrorMessage());
			}
			
			$this->S(Dyhb::L('喜欢帖子成功，可以到个人中心查看你喜欢的帖子','Controller/Grouptopic'));
		}
	}

}
