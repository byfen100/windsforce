<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   精华或者取消精华帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class DigesttopicController extends Controller{

	public function index(){
		$sGrouptopics=trim(G::getGpc('grouptopics'));
		$nGroupid=intval(G::getGpc('groupid'));
		$nStatus=intval(G::getGpc('status'));
		$sReason=trim(G::getGpc('reason'));

		if(!Groupadmin_Extend::checkTopicadminRbac($nGroupid,array('group@grouptopicadmin@digesttopic'))){
			$this->E(Dyhb::L('你没有精华或者取消精华帖子的权限','Controller/Grouptopicadmin'));
		}
		
		$arrGrouptopics=explode(',',$sGrouptopics);

		$bAdmincredit=false;

		if(!$sReason){
			$sReason=Dyhb::L('该管理人员没有填写操作原因','Controller/Grouptopicadmin');
		}
		
		if(is_array($arrGrouptopics)){
			foreach($arrGrouptopics as $nGrouptopic){
				$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nGrouptopic)->getOne();

				if(!empty($oGrouptopic['grouptopic_id']) && $oGrouptopic->grouptopic_addtodigest!=$nStatus){
					$bNeedcredit=false;
					if($oGrouptopic->grouptopic_addtodigest<$nStatus && $nStatus>0){
						$bNeedcredit=true;
					}
					
					$oGrouptopic->grouptopic_addtodigest=$nStatus;
					$oGrouptopic->setAutofill(false);
					$oGrouptopic->save(0,'update');
					
					if($oGrouptopic->isError()){
						$this->E($oGrouptopic->getErrorMessage());
					}

					if($bNeedcredit===true){
						Core_Extend::updateCreditByAction('group_topicdigest'.$nStatus,$oGrouptopic['user_id']);
					}

					// 发送提醒
					if($bSendnotice===true && $GLOBALS['___login___']['user_id']!=$oGrouptopic['user_id']){
						$sNoticetemplate='<div class="notice_'.($nStatus==1?'digestgrouptopic':'undigestgrouptopic').'"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.($nStatus==1?Dyhb::L('对你的主题执行了精华操作','Controller/Grouptopicadmin'):Dyhb::L('对你的主题执行了取消精华操作','Controller/Grouptopicadmin')).'&nbsp;<a href="{@grouptopic_link}">'.$oGrouptopic['grouptopic_title'].'</a></span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{admin_reason}</span></div>&nbsp;'.Dyhb::L('如果你对该操作有任何疑问，可以联系相关人员咨询','Controller/Grouptopicadmin').'</div><div class="notice_action"><a href="{@grouptopic_link}">'.Dyhb::L('查看','Controller/Grouptopicadmin').'</a></div></div>';

						$arrNoticedata=array(
							'@space_link'=>'group://space@?id='.$GLOBALS['___login___']['user_id'],
							'user_name'=>$GLOBALS['___login___']['user_name'],
							'@grouptopic_link'=>'group://grouptopic/view?id='.$oGrouptopic['grouptopic_id'],
							'admin_reason'=>$sReason,
						);

						try{
							Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$oGrouptopic['user_id'],($nStatus==1?'digestgrouptopic':'undigestgrouptopic'),$oGrouptopic['grouptopic_id']);
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
			Core_Extend::updateCreditByAction('group_topicadmin',$GLOBALS['___login___']['user_id']);
		}

		$this->A(array('group_id'=>$nGroupid),$nStatus==0?Dyhb::L('取消主题精华成功','Controller/Grouptopicadmin'):Dyhb::L('主题设置精华成功','Controller/Grouptopicadmin'));
	}

}
