<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   置顶或者取消置顶多个回帖控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class StickreplycommentController extends Controller{

	public function index(){
		$nGrouptopics=intval(G::getGpc('grouptopics'));
		$sGrouptopiccomments=trim(G::getGpc('grouptopiccomments'));
		$nGroupid=intval(G::getGpc('groupid'));
		$nStatus=intval(G::getGpc('status'));
		$sReason=trim(G::getGpc('reason'));

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopics)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你操作回帖的主题不存在','Controller/Grouptopicadmin'));
		}

		if(!Groupadmin_Extend::checkCommentadminRbac($oGrouptopic->group,array('group@grouptopicadmin@stickreplycomment'))){
			$this->E(Dyhb::L('你没有权限置顶或者取消置顶回帖','Controller/Grouptopicadmin'));
		}
		
		$arrGrouptopiccomments=explode(',',$sGrouptopiccomments);

		$bAdmincredit=false;

		if(!$sReason){
			$sReason=Dyhb::L('该管理人员没有填写操作原因','Controller/Grouptopicadmin');
		}
		
		if(is_array($arrGrouptopiccomments)){
			foreach($arrGrouptopiccomments as $nGrouptopiccomment){
				$oGrouptopiccomment=GrouptopiccommentModel::F('grouptopiccomment_id=?',$nGrouptopiccomment)->getOne();

				if(!empty($oGrouptopiccomment['grouptopiccomment_id']) && $oGrouptopiccomment->grouptopiccomment_stickreply!=$nStatus){
					$bNeedcredit=false;
					if($oGrouptopiccomment->grouptopiccomment_stickreply<$nStatus && $nStatus>0){
						$bNeedcredit=true;
					}
					
					$oGrouptopiccomment->grouptopiccomment_stickreply=$nStatus;
					$oGrouptopiccomment->setAutofill(false);
					$oGrouptopiccomment->save(0,'update');
					
					if($oGrouptopiccomment->isError()){
						$this->E($oGrouptopiccomment->getErrorMessage());
					}

					if($bNeedcredit===true){
						Core_Extend::updateCreditByAction('group_stickreply',$oGrouptopiccomment['user_id']);
					}

					// 发送提醒
					if($GLOBALS['___login___']['user_id']!=$oGrouptopiccomment['user_id']){
						$sNoticetemplate='<div class="notice_'.($nStatus==1?'stickreplycomment':'unstickreplycomment').'"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.($nStatus==1?Dyhb::L('对你的回帖执行了置顶','Controller/Grouptopicadmin'):Dyhb::L('对你的回帖执行了取消置顶','Controller/Grouptopicadmin')).'</span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{admin_reason}</span></div>&nbsp;'.Dyhb::L('如果你对该操作有任何疑问，可以联系相关人员咨询','Controller/Grouptopicadmin').'</div><div class="notice_action"><a href="{@grouptopiccomment_link}">'.Dyhb::L('查看','Controller/Grouptopicadmin').'</a></div></div>';

						$arrNoticedata=array(
							'@space_link'=>'group://space@?id='.$GLOBALS['___login___']['user_id'],
							'user_name'=>$GLOBALS['___login___']['user_name'],
							'@grouptopiccomment_link'=>'group://grouptopic/view?id='.$oGrouptopiccomment['grouptopic_id'].'&isolation_commentid='.$oGrouptopiccomment['grouptopiccomment_id'],
							'admin_reason'=>$sReason,
						);

						try{
							Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$oGrouptopiccomment['user_id'],($nStatus==1?'stickreplycomment':'unstickreplycomment'),$oGrouptopiccomment['grouptopiccomment_id']);
						}catch(Exception $e){
							$this->E($e->getMessage());
						}
					}

					$bAdmincredit=true;
				}
			}
			
		}

		// 管理积分
		if($bAdmincredit===true){
			Core_Extend::updateCreditByAction('group_commentadmin',$GLOBALS['___login___']['user_id']);
		}

		$sGrouptopicurl=Dyhb::U('group://topic@?id='.$oGrouptopic['grouptopic_id']);

		$this->A(array('group_id'=>$nGroupid,'grouptopic_url'=>$sGrouptopicurl),$nStatus==1?Dyhb::L('置顶回帖成功','Controller/Grouptopicadmin'):Dyhb::L('取消置顶回帖成功','Controller/Grouptopicadmin'));
	}

}
