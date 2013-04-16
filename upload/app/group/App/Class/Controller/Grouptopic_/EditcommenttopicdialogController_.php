<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   编辑回复对话框控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class EditcommenttopicdialogController extends Controller{

	public function index(){
		$nGrouptopiccommentid=intval(G::getGpc('cid','G'));

		if(!$nGrouptopiccommentid){
			$this->E(Dyhb::L('你没有指定编辑的回帖的ID','Controller/Grouptopic'));
		}

		$oGrouptopiccomment=GrouptopiccommentModel::F('grouptopiccomment_id=?',$nGrouptopiccommentid)->getOne();
		if(empty($oGrouptopiccomment['grouptopiccomment_id'])){
			$this->E(Dyhb::L('你要编辑的回帖不存在','Controller/Grouptopic'));
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

		// 取得个人主页
		$oUserprofile=UserprofileModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getOne();

		$this->assign('oEditGrouptopiccomment',$oGrouptopiccomment);
		$this->assign('oGrouptopic',$oGrouptopic);

		$this->display('grouptopic+editcommenttopicdialog');
	}

}
