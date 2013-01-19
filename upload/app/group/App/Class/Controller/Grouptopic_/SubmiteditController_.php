<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   处理帖子编辑控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SubmiteditController extends Controller{

	public function index(){
		$nGid=intval(G::getGpc('group_id'));
		$nTid=intval(G::getGpc('grouptopic_id'));

		$oGrouptopic=GrouptopicModel::F('group_id=? AND grouptopic_id=?',$nGid,$nTid)->getOne();
		if(empty($oGrouptopic->group_id)){
			$this->E(Dyhb::L('你访问的主题不存在或已删除','Controller/Grouptopic'));
		}

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGrouptopic['group_id'],true);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		if(!Groupadmin_Extend::checkTopicedit($oGrouptopic)){
			$this->E(Dyhb::L('你没有权限编辑帖子','Controller/Grouptopic'));
		}

		// 处理checkbox
		$arrCheckbox=array(
			'grouptopic_usesign','grouptopic_isanonymous','grouptopic_hiddenreplies',
			'grouptopic_ordertype','grouptopic_allownoticeauthor','grouptopic_iscomment',
			'grouptopic_sticktopic','grouptopic_addtodigest','grouptopic_isrecommend',
			'grouptopic_onlycommentview',
		);

		foreach($arrCheckbox as $sCheckbox){
			if(!isset($_POST[$sCheckbox])){
				$_POST[$sCheckbox]=0;
			}
		}

		$nIsrecommend=$oGrouptopic->grouptopic_isrecommend;
		$nAddtodigest=$oGrouptopic->grouptopic_addtodigest;
		$nSticktopic=$oGrouptopic->grouptopic_sticktopic;
		
		$oGrouptopic->grouptopic_updateusername=$GLOBALS['___login___']['user_name'];
		$oGrouptopic->save(0,'update');

		if($oGrouptopic->isError()){
			$this->E($oGrouptopic->getErrorMessage());
		}

		// 更新积分
		if($oGrouptopic->grouptopic_addtodigest>0 && $nAddtodigest<$oGrouptopic->grouptopic_addtodigest){
			Core_Extend::updateCreditByAction('group_topicdigest'.$oGrouptopic['grouptopic_addtodigest'],$oGrouptopic['user_id']);
		}

		if($oGrouptopic->grouptopic_sticktopic>0 && $nSticktopic<$oGrouptopic->grouptopic_sticktopic){
			Core_Extend::updateCreditByAction('group_topicstick'.$oGrouptopic['grouptopic_sticktopic'],$oGrouptopic['user_id']);
		}

		if($oGrouptopic->grouptopic_isrecommend>0 && $nIsrecommend<$oGrouptopic->grouptopic_isrecommend){
			Core_Extend::updateCreditByAction('group_trecommend'.$oGrouptopic['grouptopic_isrecommend'],$oGrouptopic['user_id']);
		}

		// 保存帖子标签
		$sTags=trim(G::getGpc('tags','P'));
		$sOldTags=trim(G::getGpc('old_tags','P'));

		$oGrouptopictag=Dyhb::instance('GrouptopictagModel');
		$oGrouptopictag->addTag($oGrouptopic->grouptopic_id,$sTags,$sOldTags);

		if($oGrouptopictag->isError()){
			$this->E($oGrouptopictag->getErrorMessage());
		}

		$sUrl=Dyhb::U('group://topic@?id='.$nTid);
		$this->A(array('url'=>$sUrl),Dyhb::L('主题编辑成功','Controller/Grouptopic'),1);
	}

}
