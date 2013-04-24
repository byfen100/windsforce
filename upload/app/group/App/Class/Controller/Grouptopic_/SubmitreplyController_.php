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

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGrouptopic['group_id'],true);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		if(!Groupadmin_Extend::checkCommentRbac($oGrouptopic->group,$oGrouptopiccomment)){
			$this->E(Dyhb::L('你没有权限编辑回帖','Controller/Grouptopic'));
		}

		$sContent=trim($_POST['grouptopiccomment_message']);
		$sContent=rtrim($sContent,'<br />');

		// 保存回复数据
		$oGrouptopiccomment->grouptopiccomment_content=$sContent;
		$oGrouptopiccomment->grouptopiccomment_title=trim($_POST['grouptopiccomment_title']);
		$oGrouptopiccomment->setAutofill(false);
		$oGrouptopiccomment->save(0,'update');

		if($oGrouptopiccomment->isError()){
			$this->E($oGrouptopiccomment->getErrorMessage());
		}

		// 发送提醒
		if($GLOBALS['___login___']['user_id']!=$oGrouptopiccomment['user_id']){
			$sGrouptopiccommentmessage=G::subString(strip_tags($oGrouptopiccomment['grouptopiccomment_content']),0,100);
			
			$sNoticetemplate='<div class="notice_editgrouptopiccomment"><span class="notice_title"><a href="{@space_link}">{user_name}</a>&nbsp;'.Dyhb::L('编辑了你的回帖','Controller/Grouptopic').'</span><div class="notice_content"><div class="notice_quote"><span class="notice_quoteinfo">{content_message}</span></div>&nbsp;'.Dyhb::L('如果你对该操作有任何疑问，可以联系相关人员咨询','Controller/Grouptopic').'</div><div class="notice_action"><a href="{@grouptopiccomment_link}">'.Dyhb::L('查看','Controller/Grouptopic').'</a></div></div>';

			$arrNoticedata=array(
				'@space_link'=>'group://space@?id='.$GLOBALS['___login___']['user_id'],
				'user_name'=>$GLOBALS['___login___']['user_name'],
				'@grouptopiccomment_link'=>'group://grouptopic/view?id='.$oGrouptopiccomment['grouptopic_id'].'&isolation_commentid='.$oGrouptopiccomment['grouptopiccomment_id'],
				'content_message'=>$sGrouptopiccommentmessage,
			);

			try{
				Core_Extend::addNotice($sNoticetemplate,$arrNoticedata,$oGrouptopiccomment['user_id'],'editgrouptopiccomment',$oGrouptopiccomment['grouptopiccomment_id']);
			}catch(Exception $e){
				$this->E($e->getMessage());
			}
		}

		$nTotalComment=GrouptopiccommentModel::F('grouptopic_id=?',$oGrouptopic->grouptopic_id)->getCounts();
		$nPage=ceil($nTotalComment/$GLOBALS['_cache_']['group_option']['grouptopic_listcommentnum']);
		
		$sUrl=Dyhb::U('group://topic@?id='.$oGrouptopic->grouptopic_id.($nPage>1?'&page='.$nPage:'').'&extra=new'.$oGrouptopiccomment->grouptopiccomment_id).'#grouptopiccomment-'.($oGrouptopiccomment->grouptopiccomment_id);

		$this->A(array('url'=>$sUrl),Dyhb::L('编辑回帖成功','Controller/Grouptopic'),1);
	}

}
