<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   编辑帖子回复控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class SubmitreplyController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('editcid'));

		if($GLOBALS['___login___']===false){
			$this->E(Dyhb::L('你没有登录无法编辑回帖','Controller/Grouptopic'));
		}

		if(empty($nId)){
			$this->E(Dyhb::L('你没有指定编辑回帖的ID','Controller/Grouptopic'));
		}

		$oGrouptopiccomment=GrouptopiccommentModel::F('grouptopiccomment_id=?',$nId)->getOne();
		if(empty($oGrouptopiccomment['grouptopiccomment_id'])){
			$this->E(Dyhb::L('你编辑的回帖不存在','Controller/Grouptopic'));
		}

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$oGrouptopiccomment['grouptopic_id'])->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你要编辑的回帖的主题不存在','Controller/Grouptopic'));
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

		$sContent=trim($_POST['grouptopiccomment_message']);
		$sContent=rtrim($sContent,'<br />');

		// 保存回复数据
		$oGrouptopiccomment->grouptopiccomment_content=$sContent;
		$oGrouptopiccomment->setAutofill(false);
		$oGrouptopiccomment->save(0,'update');

		if($oGrouptopiccomment->isError()){
			$this->E($oGrouptopiccomment->getErrorMessage());
		}

		$nTotalComment=GrouptopiccommentModel::F('grouptopic_id=?',$oGrouptopic->grouptopic_id)->getCounts();
		$nPage=ceil($nTotalComment/$GLOBALS['_cache_']['group_option']['grouptopic_listcommentnum']);
		
		$sUrl=Dyhb::U('group://topic@?id='.$oGrouptopic->grouptopic_id.($nPage>1?'&page='.$nPage:'').'&extra=new'.$oGrouptopiccomment->grouptopiccomment_id).'#grouptopiccomment-'.($oGrouptopiccomment->grouptopiccomment_id);

		$this->A(array('url'=>$sUrl),Dyhb::L('编辑回帖成功','Controller/Grouptopic'),1);
	}

}
