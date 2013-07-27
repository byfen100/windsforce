<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子阅读模式控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ReadtopicController extends Controller{

	public function index(){
		if(!Core_Extend::checkRbac('group@grouptopic@view')){
			$this->E(Dyhb::L('你没有权限查看帖子','Controller'));
		}
		
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
		
		$this->_oGrouptopic=$oGrouptopic;

		// 更新点击量
		$oGrouptopic->grouptopic_views=$oGrouptopic->grouptopic_views+1;
		$oGrouptopic->setAutofill(false);
		$oGrouptopic->save(0,'update');

		if($oGrouptopic->isError()){
			$this->E($oGrouptopic->getErrorMessage());
		}

		$this->assign('oGrouptopic',$oGrouptopic);

		// 判断用户是否回复过帖子
		if($oGrouptopic['grouptopic_onlycommentview']==1){
			$bHavecomment=false;

			if($GLOBALS['___login___']!==false){
				if($oGrouptopic['user_id']==$GLOBALS['___login___']['user_id']){
					$bHavecomment=true;
				}else{
					$oTrygrouptopiccomment=GrouptopiccommentModel::F('user_id=? AND grouptopic_id=?',$GLOBALS['___login___']['user_id'],$oGrouptopic['grouptopic_id'])->getOne();

					if(!empty($oTrygrouptopiccomment['grouptopiccomment_id'])){
						$bHavecomment=true;
					}
				}
			}

			$this->assign('bHavecomment',$bHavecomment);
		}

		$this->display('grouptopic+readtopic');
	}

}
