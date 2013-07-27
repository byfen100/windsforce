<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   删除回帖控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DeletecommentController extends Controller{

	public function index(){
		$nGrouptopics=intval(G::getGpc('grouptopics'));
		$sGrouptopiccomments=trim(G::getGpc('grouptopiccomments'));
		$nGroupid=intval(G::getGpc('groupid'));
		$sReason=trim(G::getGpc('reason'));

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopics)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你操作回帖的主题不存在','Controller'));
		}

		if(!Groupadmin_Extend::checkCommentadminRbac($oGrouptopic->group,array('group@grouptopicadmin@deletecomment'))){
			$this->E(Dyhb::L('你没有权限删除回帖','Controller'));
		}

		$arrGrouptopiccomments=explode(',',$sGrouptopiccomments);

		$bAdmincredit=false;

		if(!$sReason){
			$sReason=Dyhb::L('该管理人员没有填写操作原因','Controller');
		}
		
		if(is_array($arrGrouptopiccomments)){
			foreach($arrGrouptopiccomments as $nGrouptopiccomment){
				$oGrouptopiccomment=GrouptopiccommentModel::F('grouptopiccomment_id=? AND grouptopiccomment_status=1',$nGrouptopiccomment)->getOne();

				if(!empty($oGrouptopiccomment['grouptopiccomment_id'])){
					$nUserid=$oGrouptopiccomment['user_id'];
					$nGrouptopiccommentid=$oGrouptopiccomment['grouptopiccomment_id'];
					$nGrouptopicid=$oGrouptopiccomment['grouptopic_id'];
					$sGrouptopictitle=$oGrouptopiccomment->grouptopic->grouptopic_title;

					// 回帖回收站功能开启
					if($GLOBALS['_cache_']['group_option']['group_deletecomment_recyclebin']==1){
						$oGrouptopiccomment->grouptopiccomment_status='0';
						$oGrouptopiccomment->save(0,'update');

						if($oGrouptopiccomment->isError()){
							$this->E($oGrouptopiccomment->getErrorMessage());
						}
					}else{
						$oGrouptopiccommentMeta=GrouptopiccommentModel::M();
						$oGrouptopiccommentMeta->deleteWhere(array('grouptopiccomment_id'=>$nGrouptopiccomment));
							
						if($oGrouptopiccommentMeta->isError()){
							$this->E($oGrouptopiccommentMeta->getErrorMessage());
						}
					}
					
					// 发送提醒
					if($GLOBALS['___login___']['user_id']!=$nUserid){
						$sNoticetemplate='<div class="notice_deletecomment"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('对你的回帖执行了删除','Controller').'&nbsp;Reply:<a href="{@grouptopic_link}">'.$sGrouptopictitle.'</a>'.'</span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{admin_reason}</span></div>&nbsp;'.($GLOBALS['_cache_']['group_option']['group_deletecomment_recyclebin']==1?Dyhb::L('注意，系统开启了回帖回收站功能，该回帖仍可以被恢复','Controller'):Dyhb::L('注意，系统未开启回帖回收站功能，该回帖已被永久删除','Controller')).'&nbsp;&nbsp;'.Dyhb::L('如果你对该操作有任何疑问，可以联系相关人员咨询','Controller').'</div><div class="notice_action"><a href="{@grouptopiccomment_link}">'.Dyhb::L('查看','Controller').'</a></div></div>';

						$arrNoticedata=array(
							'@space_link'=>'group://space@?id='.$GLOBALS['___login___']['user_id'],
							'user_name'=>$GLOBALS['___login___']['user_name'],
							'@grouptopic_link'=>'group://grouptopic/view?id='.$nGrouptopicid,
							'@grouptopiccomment_link'=>'group://grouptopic/view?id='.$nGrouptopicid.'&isolation_commentid='.$nGrouptopiccommentid,
							'admin_reason'=>$sReason,
						);

						try{
							Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$nUserid,'notice_deletecomment',$nGrouptopiccommentid);
						}catch(Exception $e){
							$this->E($e->getMessage());
						}
					}

					Core_Extend::updateCreditByAction('group_commentdelete',$nUserid);

					$bAdmincredit=true;
				}
			}

			if($GLOBALS['_cache_']['group_option']['group_deletecomment_recyclebin']=='0'){
				// 更新帖子评论数量
				$oGrouptopic->grouptopic_comments=GrouptopiccommentModel::F('grouptopic_id=?',$nGrouptopics)->all()->getCounts();
				$oGrouptopic->setAutofill(false);
				$oGrouptopic->save(0,'update');

				if($oGrouptopic->isError()){
					$this->E($oGrouptopic->getErrorMessage());
				}
			}
		}

		// 管理积分
		if($bAdmincredit===true){
			Core_Extend::updateCreditByAction('group_commentadmin',$GLOBALS['___login___']['user_id']);
		}

		$sGrouptopicurl=Dyhb::U('group://topic@?id='.$oGrouptopic['grouptopic_id']);

		$this->A(array('group_id'=>$nGroupid,'grouptopic_url'=>$sGrouptopicurl),Dyhb::L('删除回帖成功','Controller'));
	}

}
