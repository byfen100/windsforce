<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组Wap帖子阅读控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ShowController extends GlobalchildController{

	protected $_oGrouptopic=null;
	
	public function index(){
		if(!Core_Extend::checkRbac('group@grouptopic@view')){
			$this->_oParentcontroller->wap_mes(Dyhb::L('你没有权限查看帖子','Controller'),'',0);
		}
		
		// 获取参数
		$nId=intval(G::getGpc('id','G')); // 帖子ID

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=? AND grouptopic_status=1',$nId)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->_oParentcontroller->wap_mes(Dyhb::L('你访问的主题不存在或已删除','Controller'),'',0);
		}

		// 判断帖子小组
		$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$oGrouptopic->group_id)->getOne();

		if(empty($oGroup['group_id'])){
			$this->_oParentcontroller->wap_mes(Dyhb::L('小组不存在或在审核中','Controller'),'',0);
		}

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGroup);
		}catch(Exception $e){
			$this->_oParentcontroller->wap_mes($e->getMessage(),'',0);
		}

		$this->_oGrouptopic=$oGrouptopic;

		// 更新点击量
		$oGrouptopic->grouptopic_views=$oGrouptopic->grouptopic_views+1;
		$oGrouptopic->setAutofill(false);
		$oGrouptopic->save(0,'update');

		if($oGrouptopic->isError()){
			$this->_oParentcontroller->wap_mes($oGrouptopic->getErrorMessage(),'',0);
		}

		if($oGrouptopic->grouptopic_thumb>0){
			$oGrouptopic->grouptopic_content='<div class="grouptopicthumb"><div class="grouptopicthumb_title">'.Dyhb::L('主题缩略图','Controller').'</div><p>[attachment]'.$oGrouptopic->grouptopic_thumb.'[/attachment]</p></div>'.$oGrouptopic->grouptopic_content;
		}
		
		$this->assign('oGrouptopic',$oGrouptopic);
		$this->assign('oGroup',$oGrouptopic->group);
		
		$this->display('wap+show');
	}

	public function show_title_(){
		return $this->_oGrouptopic['grouptopic_title'].' - '.$this->_oGrouptopic->group->group_nikename;
	}

	public function show_keywords_(){
		return $this->show_title_();
	}

	public function index_description_(){
		return $this->show_title_();
	}

}
