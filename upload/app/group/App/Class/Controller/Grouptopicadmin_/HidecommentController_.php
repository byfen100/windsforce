<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   屏蔽或者显示多个回帖控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HidecommentController extends Controller{

	public function index(){
		$nGrouptopics=intval(G::getGpc('grouptopics'));
		$sGrouptopiccomments=trim(G::getGpc('grouptopiccomments'));
		$nGroupid=intval(G::getGpc('groupid'));
		$nStatus=intval(G::getGpc('status'));
		$sReason=trim(G::getGpc('reason'));

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopics)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('你操作回帖的主题不存在','Controller'));
		}

		if(!Groupadmin_Extend::checkCommentadminRbac($oGrouptopic->group,array('group@grouptopicadmin@hidecomment'))){
			$this->E(Dyhb::L('你没有权限屏蔽或者显示回帖','Controller'));
		}
		
		$arrGrouptopiccomments=explode(',',$sGrouptopiccomments);

		$bAdmincredit=false;

		if(!$sReason){
			$sReason=Dyhb::L('该管理人员没有填写操作原因','Controller');
		}
		
		if(is_array($arrGrouptopiccomments)){
			foreach($arrGrouptopiccomments as $nGrouptopiccomment){
				$oGrouptopiccomment=GrouptopiccommentModel::F('grouptopiccomment_id=?',$nGrouptopiccomment)->getOne();

				if(!empty($oGrouptopiccomment['grouptopiccomment_id']) && $oGrouptopiccomment->grouptopiccomment_ishide!=$nStatus){
					$oGrouptopiccomment->grouptopiccomment_ishide=$nStatus;
					$oGrouptopiccomment->setAutofill(false);
					$oGrouptopiccomment->save(0,'update');
					
					if($oGrouptopiccomment->isError()){
						$this->E($oGrouptopiccomment->getErrorMessage());
					}

					// 发送提醒
					if($GLOBALS['___login___']['user_id']!=$oGrouptopiccomment['user_id']){
						$sNoticetemplate='<div class="notice_'.($nStatus==1?'hidereplycomment':'showreplycomment').'"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.($nStatus==1?Dyhb::L('对你的回帖执行了屏蔽','Controller'):Dyhb::L('对你的回帖执行了显示','Controller')).'</span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{admin_reason}</span></div>&nbsp;'.Dyhb::L('如果你对该操作有任何疑问，可以联系相关人员咨询','Controller').'</div><div class="notice_action"><a href="{@grouptopiccomment_link}">'.Dyhb::L('查看','Controller').'</a></div></div>';

						$arrNoticedata=array(
							'@space_link'=>'group://space@?id='.$GLOBALS['___login___']['user_id'],
							'user_name'=>$GLOBALS['___login___']['user_name'],
							'@grouptopiccomment_link'=>'group://grouptopic/view?id='.$oGrouptopiccomment['grouptopic_id'].'&isolation_commentid='.$oGrouptopiccomment['grouptopiccomment_id'],
							'admin_reason'=>$sReason,
						);

						try{
							Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$oGrouptopiccomment['user_id'],($nStatus==1?'hidereplycomment':'showreplycomment'),$oGrouptopiccomment['grouptopiccomment_id']);
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

		$this->A(array('group_id'=>$nGroupid,'grouptopic_url'=>$sGrouptopicurl),$nStatus==1?Dyhb::L('屏蔽回帖成功','Controller'):Dyhb::L('取消屏蔽回帖成功','Controller'));
	}

}
