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

		// 小组验证
		$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$oGrouptopic->group_id)->getOne();
		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('你回复的帖子所在小组不存在','Controller/Grouptopic'));
		}

		if($oGroup->group_isopen==0){
			$oGroupuser=GroupuserModel::F('user_id=? AND group_id=?',$GLOBALS['___login___']['user_id'],$oGroup['group_id'])->getOne();
			if(empty($oGroupuser['user_id'])){
				$this->E(Dyhb::L('只有该小组成员才能够访问小组','Controller/Group').'&nbsp;<span id="listgroup_'.$oGroup['group_id'].'" class="commonjoinleave_group"><a href="javascript:void(0);" onclick="joinGroup('.$oGroup['group_id'].',\'listgroup_'.$oGroup['group_id'].'\');">'.Dyhb::L('我要加入','Controller/Group').'</a></span>');
			}
		}

		// 发贴权限
		if($oGroup->group_ispost==0){
			$oGroupuser=GroupuserModel::F('user_id=? AND group_id=?',$GLOBALS['___login___']['user_id'],$nGroupid)->getOne();
			if(empty($oGroupuser['user_id'])){
				$this->E(Dyhb::L('只有该小组成员才能发帖','Controller/Grouptopic').'&nbsp;<span id="listgroup_'.$oGroup['group_id'].'" class="commonjoinleave_group"><a href="javascript:void(0);" onclick="joinGroup('.$oGroup['group_id'].',\'listgroup_'.$oGroup['group_id'].'\');">'.Dyhb::L('我要加入','Controller/Group').'</a></span>');
			}
		}elseif($oGroup->group_ispost==1){
			$this->E(Dyhb::L('该小组目前拒绝任何人发帖','Controller/Grouptopic'));
		}

		if(!Groupadmin_Extend::checkCommentRbac($oGrouptopic->group,$oGrouptopiccomment)){
			$this->E(Dyhb::L('你没有权限编辑回帖','Controller/Grouptopic'));
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
		
		$oGrouptopic->grouptopic_updateusername=$GLOBALS['___login___']['user_name'];
		$oGrouptopic->save(0,'update');

		if($oGrouptopic->isError()){
			$this->E($oGrouptopic->getErrorMessage());
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
